{
	"info": {
		"_postman_id": "1324ab55-f6ef-41e3-a375-aff8968fe908",
		"name": "Keros Back",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
	},
	"item": [
		{
			"name": "Get Cat",
			"request": {
				"method": "GET",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": ""
				},
				"url": {
					"raw": "localhost:8000/api/v1/cat/1",
					"host": [
						"localhost"
					],
					"port": "8000",
					"path": [
						"api",
						"v1",
						"cat",
						"1"
					]
				}
			},
			"response": []
		},
		{
			"name": "Create Cat",
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "formdata",
					"formdata": [
						{
							"key": "height",
							"value": "7.4",
							"type": "text"
						},
						{
							"key": "name",
							"value": "Tom",
							"type": "text"
						}
					]
				},
				"url": {
					"raw": "localhost:8000/api/v1/cat",
					"host": [
						"localhost"
					],
					"port": "8000",
					"path": [
						"api",
						"v1",
						"cat"
					]
				}
			},
			"response": []
		}
	],
	"auth": {
		"type": "oauth2",
		"oauth2": [
			{
				"key": "addTokenTo",
				"value": "header",
				"type": "string"
			}
		]
	}
}