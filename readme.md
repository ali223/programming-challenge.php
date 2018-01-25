# Introduction

Todo Tasks API, allows you to view, create, update and delete your todo tasks. 

# Overview

The API tries to follow the most of [JSON API Specification](http://jsonapi.org/). However, it does not fully comply with the specification.

# Tasks Resource

Each todo task is represented by a task resource. A task resource has got the following properties:-

| Property      | Description                       | 
| ------------- | --------------------------------- | 
| id            | The unique idenitifier for  task  | 
| title         | The title of a task               | 
| completed     | Indicates if a task is completed  (true/false)   | 



# Authentication

There is no authentication required to access the API endpoints.

# Status and Error Codes

* __200 OK__ : The request was processed successfully.
* __201 Created__ : The request was processed successfully and a new resource (Task) has been created.
* __404 Not Found__ : Unable to process the request, as the resource (Task) requested, could not be found.
* __422 Unprocessable Entity__ : Data provided in the request, for example, to create a new resource (Task), failed validation.

# Errors Object

In case of validation errors, or when the given task id does not exist, an Errors object is also returned in the HTTP Response. It has got the following properties:-

| Property      | Description                             | 
| ------------- | --------------------------------------- | 
| status        | HTTP Response status code               | 
| title         | A short description of the error        | 
| details       | Details of the error                    |


# API Endpoints


## GET http://localhost:8000/api/tasks

__Example Http Request:__ `GET http://localhost:8000/api/tasks`

|Http Request Headers      |                        |
| -------------------------|----------------------- |
|Accept                    | application/json       |


Returns a list of all tasks in JSON API format.

```
{
    "links": {
        "self": "http://localhost:8000/api/tasks"
    },
    "data": [
        {
            "type": "tasks",
            "id": "1",
            "attributes": {
                "title": "Enim magni ut ut quisquam vitae.",
                "completed": false
            },
            "links": {
                "self": "http://localhost:8000/api/tasks/1"
            }
        },
        {
            "type": "tasks",
            "id": "2",
            "attributes": {
                "title": "Quia omnis debitis neque inventore.",
                "completed": false
            },
            "links": {
                "self": "http://localhost:8000/api/tasks/2"
            }
        },
        {
            "type": "tasks",
            "id": "3",
            "attributes": {
                "title": "Est excepturi alias aut.",
                "completed": false
            },
            "links": {
                "self": "http://localhost:8000/api/tasks/3"
            }
        }
    ]
}
```

## GET http://localhost:8000/api/tasks/{id}

__Example Http Request:__ `GET http://localhost:8000/api/tasks/1`

|Http Request Headers      |                        |
| -------------------------|----------------------- |
|Accept                    | application/json       |


Finds a task resource instance, with the given id, and returns it in JSON API format, like the example below:-



```
{
    "data": {
        "type": "tasks",
        "id": "1",
        "attributes": {
            "title": "Enim magni ut ut quisquam vitae.",
            "completed": false
        },
        "links": {
            "self": "http://localhost:8000/api/tasks/1"
        }
    }
}
```


If there is no task with the given id, a __404 Not Found__ Http Response is returned with the instance of Errors object, like the example below:



```
{
    "errors": {
        "status": 404,
        "title": "Resource Not Found",
        "details": "Cannot find Resource with the id 343"
    }
}
```

## POST http://localhost:8000/api/tasks

__Example Http Request:__ `POST http://localhost:8000/api/tasks`

|Http Request Headers      |                        |
| -------------------------|----------------------- |
|Accept                    | application/json       |
|Content-Type              | application/json       |

__HTTP Request Body__
```
{
    "data": {
        "type": "tasks",
        "attributes": {
            "title": "My New Task",
            "completed": false
        }
    }
}

```

Creates a new task resource and, on success, returns the __200 OK__ Http Response, with the instance of the newly created task resource in JSON API format. 

The `"type"` property is required and must have the value `"tasks"`

All the attributes (`"title"` and `"completed"`) are required. And the `"completed"` attribute must have a boolean value i.e. `true` or `false`.

If any of the attributes is missing, a __422 Unprocessable Entity__ Http Response is returned, with an Errors object, like the example below:-

```
{
    "errors": {
        "status": 422,
        "title": "The given data was invalid.",
        "details": {
            "data.attributes.title": [
                "The data.attributes.title field is required."
            ]
        }
    }
}
```

## PATCH http://localhost:8000/api/tasks/{id}

__Example Http Request:__ `PATCH http://localhost:8000/api/tasks/1`

|Http Request Headers      |                        |
| -------------------------|----------------------- |
|Accept                    | application/json       |
|Content-Type              | application/json       |

__HTTP Request Body__
```
{
    "data": {
        "type": "tasks",
        "id": 1,
        "attributes": {
            "title": "This is the Updated Title",
            "completed": true
        }
    }
}
```

Updates the task resource with the given id. On success, it returns a __200 OK__ Http Response, with an instance of the updated task resource, in JSON API format. 

The property `"type"` is required and must have the value `"tasks"`.

The property `"id"` is required. Also, <i></i>t must be the id of the task resource instance, which needs to be updated. The `"id"` property value must match with the id in the request url.

All the attributes (`"title"` and `"completed"`) are optional. 

If there is no task with the given id, a __404 Not Found__ Http Response is returned, with an Errors object, like the example below:-

```
{
    "errors": {
        "status": 404,
        "title": "Resource Not Found",
        "details": "Cannot find Resource with the id 12345"
    }
}
```

## DELETE http://localhost:8000/api/tasks/{id}

__Example Http Request:__ `DELETE http://localhost:8000/api/tasks/1`

|Http Request Headers      |                        |
| -------------------------|----------------------- |
|Accept                    | application/json       |

Deletes a task resource, with the given id. On success, it returns __200 OK__ status. 

If a task with the given id does not exist, it returns __404 Not Found__ Http Response, with an Errors object, like the example below:-

```
{
    "errors": {
        "status": 404,
        "title": "Resource Not Found",
        "details": "Cannot find Resource with the id 12345"
    }
}
```
