# Technical Test - Delivery Service

This domain service application was created in **[Slim 4](https://www.slimframework.com/)** to demonstrate the use of SOLID principles to process three types of delivery orders.
The app follows the structure of [Slim skeleton application](https://github.com/slimphp/Slim-Skeleton) with minor changes.
The app also included testing framework PHPUnit.

Types of Delivery:
1. Personal Delivery
2. Personal Delivery Express
3. Enterprise Delivery

## Install the Application

Run this command from the directory in which you want to install the Delivery Service application.

```bash
composer create-project farabidev/delivery-service [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application in development, you can run these commands 

```bash
cd [my-app-name]
composer start
```

After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

## Code Structure & Details
All endpoints are under Actions folder.

Below are few of SOLID Principles that have been implemented in the app.

Created a `Processors\Delivery` folder to store the three types of delivery in a separate class. This is to ensure any changes 
to any type of deliveries will only affect the one. (`Single Responsibility Principle`).

Created a `Services` folder to store all services used in the app. The email service follows the `Open Closed Principle`
and `Dependency Inversion Principle`. The email service has DI `EmailServiceInterface`, any new email services won't change the email service class and
reusable to any new email service as long as it implements the same interface.

The endpoint JSON responds are substitutable with different data without altering the correct format. All endpoints extend the Action Class.
(`Liskov Substitution Principle`)

Created an `Interfaces` folder to store all used interface. All classes do not implement an interface that does not
go to use. (`Interface Segregation Principle`)

Created a `Validator` folder to store any validation required to run the service successfully.

## How It Works
In order to send a delivery, the application uses an endpoint `POST /delivery/send`. 

To check all endpoints you need an HTTP client e.g [Postman](https://www.getpostman.com/).
You can use the [available collections](#Delivery Service.postman_collection.json) exported from Postman or 
see the [List of Endpoints](#list-of-endpoints) section.

Below are summarise of steps on how the delivery works:
1. Retrieve the delivery types from the JSON request.
2. Validate the `deliveryTypes` property. If valid, use the correct type else throw an error.
3. Validate the JSON request for the selected delivery type. 
    Each delivery types extend `DeliveryValidator` to check for missing / invalid properties.
    Each delivery type has a different required fields.
    * Enterprise Delivery uses an additional validator to check the ABN (`Validator/AbnValidator`). 
    For demo purposes, use `1234567890` for success result. 
    * Process Delivery Express uses an additional service to send an email campaign.
    The app has 2 email services `MailChimp` and `SendGrid`.
    For demo purposes, the default email service will be using `MailChimp` and show a success result. 
    Any other email services will be unsuccessful.
    By introducing a new property `campaign->service` in the JSON request, the app will be able to switch email service.
 4. Send a JSON Response following the below example format:
````JSON
{
"statusCode": 200,
"data": {
  "status": "Success",
  "message": "Delivery send successfully"
}
}
```` 

## List of Endpoints
### Send Delivery:
`POST /delivery/send`

##### Personal Delivery
Example request body:
```JSON
  {
    "customer":{
      "name":"Johnny Bravo",
      "address":"56 Pitt Street, 2000, Sydney"
    },
    "deliveryType":"personalDelivery",
    "source":"web",
    "weight":1500
  }
```
Returns status `Success` / `Failed`

Required fields: `customer->name`, `customer->address`, `deliveryType`, `source`, `weight`

Numeric fields: `weight`

Delivery Type: `personalDelivery`


##### Personal Delivery Express
Example request body:
```JSON
    {
      "customer":{
        "name":"Jack Ripper",
        "address":"822 Anzac Parade, 2035, Maroubra"
      },
      "deliveryType":"personalDeliveryExpress",
      "source":"email",
      "weight":2000,
      "campaign":{
        "name":"Christmas2018",
        "type":"holiday",
        "ad":"opportunity"
      }
    }
```
Returns status `Success` / `Failed`

Required fields: `customer->name`, `customer->address`, `deliveryType`, `source`, `weight`,`campaign->name`
`campaign->type`, `campaign->ad`

Numeric fields: `weight`

Delivery Type: `personalDeliveryExpress`


##### Enterprise Delivery
Example request body:
```JSON
    {
        "customer":{
          "name":"Elvis Presley",
          "address":"333 George Street, 2000, Sydney"
        },
        "deliveryType":"enterpriseDelivery",
        "source":"direct",
        "onBehalf":"True Capital",
        "enterprise":{
          "name":"Bayview Motel",
          "type":"PtyLtd",
          "abn":"SN123OK",
          "directors":[
            {
              "name":"Michael Jackskon",
              "address":"242 Bayview, 2434, Sydney"
            },
            {
              "name":"Freddie Mercury",
              "address":"132 Coast, 2354, Newcastle"
            }
          ]
        },
        "weight":5000
      }
```
Returns status `Success` / `Failed`

Required fields: `customer->name`, `customer->address`, `deliveryType`, `source`, `weight`,
`enterprise->name`, `enterprise->type`, `enterprise->abn`

Numeric fields: `weight`

Delivery Type: `enterpriseDelivery`