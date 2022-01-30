# PHP Hackathon
This document has the purpose of summarizing the main functionalities your application managed to achieve from a technical perspective. Feel free to extend this template to meet your needs and also choose any approach you want for documenting your solution.

## Problem statement
*Congratulations, you have been chosen to handle the new client that has just signed up with us.  You are part of the software engineering team that has to build a solution for the new client’s business.
Now let’s see what this business is about: the client’s idea is to build a health center platform (the building is already there) that allows the booking of sport programmes (pilates, kangoo jumps), from here referred to simply as programmes. The main difference from her competitors is that she wants to make them accessible through other applications that already have a user base, such as maybe Facebook, Strava, Suunto or any custom application that wants to encourage their users to practice sport. This means they need to be able to integrate our client’s product into their own.
The team has decided that the best solution would be a REST API that could be integrated by those other platforms and that the application does not need a dedicated frontend (no html, css, yeeey!). After an initial discussion with the client, you know that the main responsibility of the API is to allow users to register to an existing programme and allow admins to create and delete programmes.
When creating programmes, admins need to provide a time interval (starting date and time and ending date and time), a maximum number of allowed participants (users that have registered to the programme) and a room in which the programme will take place.
Programmes need to be assigned a room within the health center. Each room can facilitate one or more programme types. The list of rooms and programme types can be fixed, with no possibility to add rooms or new types in the system. The api does not need to support CRUD operations on them.
All the programmes in the health center need to fully fit inside the daily schedule. This means that the same room cannot be used at the same time for separate programmes (a.k.a two programmes cannot use the same room at the same time). Also the same user cannot register to more than one programme in the same time interval (if kangoo jumps takes place from 10 to 12, she cannot participate in pilates from 11 to 13) even if the programmes are in different rooms. You also need to make sure that a user does not register to programmes that exceed the number of allowed maximum users.
Authentication is not an issue. It’s not required for users, as they can be registered into the system only with the (valid!) CNP. A list of admins can be hardcoded in the system and each can have a random string token that they would need to send as a request header in order for the application to know that specific request was made by an admin and the api was not abused by a bad actor. (for the purpose of this exercise, we won’t focus on security, but be aware this is a bad solution, do not try in production!)
You have estimated it takes 4 weeks to build this solution. You have 3 days. Good luck!*

## Technical documentation
### Data and Domain model
In this section, please describe the main entities you managed to identify, the relationships between them and how you mapped them in the database.
![img.png](img.png)

I identified four entities: Customer, Room, Programme, Appointment
- A Customer can have many Appointments. An Appointment can have only one Customer (1:m)
- A Room can host multiple Programmes. A Programme can occur in only one room (m:1)

### Application architecture
In this section, please provide a brief overview of the design of your application and highlight the main components and the interaction between them.

This application has Models and Model related logic situated in src/Entity and src/Repository
It also has Controllers. The purpose of the Controller is to handle a Request from the Client(used Postman in development).
Using Models the Controller creates a Response and returns it to the Client in a JSON format.

###  Implementation
##### Functionalities
For each of the following functionalities, please tick the box if you implemented it and describe its input and output in your application:

[x] Brew coffee \
[x] Create programme \
[x] Delete programme \
[x] Book a programme 

##### Business rules
Please highlight all the validations and mechanisms you identified as necessary in order to avoid inconsistent states and apply the business logic in your application.

To perform any request you need to add a token in the request body

# Create programme
- Handles only POST requests
- Start time and end time must be inside a hardcoded schedule 08-20
- start time cand not be after end time
- The room in which the programme is supposed to happen must exist

# Delete Programme
- Handles only delete requests
- The programme that we are requested to delete must exist

# Add Appointment 
- The customer for which we want to make an appointment must exist
- The programme for which we want to make an appointment must exist
- The number of existing appointments must be lower than max participants for a new appointment to be made.


##### 3rd party libraries (if applicable)
Please give a brief review of the 3rd party libraries you used and how/ why you've integrated them into your project.

- I used Doctrine ORM to communicate with the DB
- I used the maker bundle to create entities and repositories as presented in the Symfony Documentation
- I used Postman to send requests

##### Environment
Please fill in the following table with the technologies you used in order to work at your application. Feel free to add more rows if you want us to know about anything else you used.
| Name | Choice |
| ------ | ------ |
| Operating system (OS) | Windows |
| Database  | Maria DB 10.4.22 via XAMPP|
| Web server| Symfony Local Server |
| PHP | 8.0.2 |
| IDE | PhpStorm |

### Testing
In this section, please list the steps and/ or tools you've used in order to test the behaviour of your solution.

I manually tested the application

## Feedback
In this section, please let us know what is your opinion about this experience and how we can improve it:

1. Have you ever been involved in a similar experience? If so, how was this one different?
I did something similar during a previous internship.

2. Do you think this type of selection process is suitable for you?
I think the problem was really hard, harder than other internship interviews I did.

3. What's your opinion about the complexity of the requirements?
I think that the requirement was really complex.

4. What did you enjoy the most?
I enjoyed developing an API and not having to think about frontend views

5. What was the most challenging part of this anti hackathon?
The most challenging part were the validations.

6. Do you think the time limit was suitable for the requirements?
I think a week would have been a more suitable time limit.

7. Did you find the resources you were sent on your email useful?
Yes

8. Is there anything you would like to improve to your current implementation?
I'm sure the application could be improved specially on the validation part.

9. What would you change regarding this anti hackathon?

