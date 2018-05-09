# GameScore

## TASK

You should create two API endpoits - one for storing a gamescore, second for providing top
10 players in particular game (game ID is specified in request parameters).
Client (JavaScript) creates a xhr request on this api endpoint and uses jsonrpc schema
(http://www.jsonrpc.org/specification). Request payload is carrying game ID (int), user ID (int) and game score (int),
PHP application receives this request, stores gameplay data into the game leaderboard and
returns a success response. It doesn&#39;t matter whether the application runs on php fpm, some php server or anything
different

**Technical requirements:**
- Use nette/di
- Storing leaderboards into database

**Input data validation:**
- It is required of you to validate input data
- Again, use jsonrpc in validation (and other) error responses

**Bonus points:**
- It would be cool for use to just clone a git repo, write `docker-compose up`
- It would be even more cool for the players to have the same ranking as other people
with the same score

## Usage

Run the project with:

```bash
docker-compose up
```

Now you can access the project from browser at URL:

```
http://localhost:9080
```

Or Adminer at URL:

```
http://localhost:9090
```

To access the project database from Adminer, use these credentials:

Host: `mysql`
Username: `gamescore_user`
Password: `masterkey`
Database: `gamescore`

## API

The version of JSON-RPC must be exactly **2.0**, and it must be specified at each request object. HTTP request could be made with methods **GET** or **POST**, do not forget required headers **Accept** and **Content-Type** that must be specified.

Required headers:
* Accept - `application/json-rpc`, `application/json`, `application/jsonrequest`
* Content-Type - `application/json-rpc`, `application/json`, `application/jsonrequest`

Possible HTTP methods:
* `GET`
* `POST`

## Scoreboard endpoint
```
http://localhost:9080/api/scoreboard
```

Methods accessible on the endpoint:
* `save` - saves the score for specified game and user
* `top` - returns an array of top score for specified game

### Save 

Request:

```json
{
	"jsonrpc": "2.0",
	"method": "save",
	"id": 1,
	"params": {
		"userId": 1,
		"gameId": 1,
		"score": 175
	}
}
```

or batch request

```json
[
	{
		"jsonrpc": "2.0",
		"method": "save",
		"id": 2,
		"params": {
			"userId": 2,
			"gameId": 1,
			"score": 175
		}
	},
	{
		"jsonrpc": "2.0",
		"method": "save",
		"id": 3,
		"params": {
			"userId": 3,
			"gameId": 1,
			"score": 160
		}
	}

]
```

Response:
```json
{
	"jsonrpc": "2.0",
	"id": 1,
	"result": "ok"
}
```

or batch response

```json
[
    {
        "jsonrpc": "2.0",
        "id": 2,
        "result": "ok"
    },
    {
        "jsonrpc": "2.0",
        "id": 3,
        "result": "ok"
    }
]
```

### Top

Request:

Parameter `limit` is optional.
```json
{
	"jsonrpc": "2.0",
	"method": "top",
	"id": 4,
	"params": {
		"gameId": 1,
		"limit": 3
	}
}
```

Response:
```json
{
	"jsonrpc": "2.0",
	"id": 4,
	"result": [
		{
			"rank": 1,
			"score": 175,
			"created": "2018-05-09 11:11:47",
			"game": "Fawn",
			"user": "Antonietta",
			"user_id": 1,
			"game_id": 1
		},
		{
			"rank": 1,
			"score": 175,
			"created": "2018-05-09 11:11:44",
			"game": "Fawn",
			"user": "Jazmin",
			"user_id": 2,
			"game_id": 1
		},
		{
			"rank": 2,
			"score": 160,
			"created": "2018-05-09 11:11:30",
			"game": "Fawn",
			"user": "Wilma",
			"user_id": 3,
			"game_id": 1
		}
	]
}
```