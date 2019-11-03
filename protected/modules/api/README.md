# [HumHub](https://github.com/humhub/humhub) Api

## Pre-Release v 0.9

This module is for HumHub Version 0.20 (Yii 2) and greater and cannot be used with HumHub Version 0.1x (Yii 1.1)

## Description

This module implements a RESTful API for Humhub installations.  Returning JSON or XML, developers can make use of
the end points to create applications, implement combined logins, create AJAX web applications, etc.

Only core module access is supported, not optional plugins.

All api calls require an access token (api_key).  Access granted via the admin panel via database authentication.

Sample Call: http://localhost/user/search/:text?access_token=:api_key

Sample Output:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<response>
   <Comment>
      <id>1</id>
      <message>Nike – Just buy it. ;Wink;</message>
      <object_model>humhub\modules\post\models\Post</object_model>
      <object_id>2</object_id>
      <space_id />
      <created_at>2016-02-16 16:44:00</created_at>
      <created_by>2</created_by>
      <updated_at>2016-02-16 16:44:00</updated_at>
      <updated_by>2</updated_by>
   </Comment>
   <Comment>
      <id>2</id>
      <message>Calvin Klein – Between love and madness lies obsession.</message>
      <object_model>humhub\modules\post\models\Post</object_model>
      <object_id>2</object_id>
      <space_id />
      <created_at>2016-02-16 16:44:00</created_at>
      <created_by>3</created_by>
      <updated_at>2016-02-16 16:44:00</updated_at>
      <updated_by>3</updated_by>
   </Comment>
</response>

```
```json
[
   {
      "id":1,
      "message":"Nike – Just buy it. ;Wink;",
      "object_model":"humhub\\modules\\post\\models\\Post",
      "object_id":2,
      "space_id":null,
      "created_at":"2016-02-16 16:44:00",
      "created_by":2,
      "updated_at":"2016-02-16 16:44:00",
      "updated_by":2
   },
   {
      "id":2,
      "message":"Calvin Klein – Between love and madness lies obsession.",
      "object_model":"humhub\\modules\\post\\models\\Post",
      "object_id":2,
      "space_id":null,
      "created_at":"2016-02-16 16:44:00",
      "created_by":3,
      "updated_at":"2016-02-16 16:44:00",
      "updated_by":3
   }
]
```


The following endpoints are currently available.

### User

1. GET /api/user - returns a list of users - - optional parameter `eager=true` will return profile.
2. GET /api/user/:id - returns a user matching the user id and profile
3. GET /api/user/search/:text - returns a list of users with username matching the search criteria
4. GET /api/user/login/:username/:password - not technically a REST call, but used to authenticate a user and get its id for use in REST calls.

### Profile

1. /api/profile - returns a list of user profiles
2. /api/profile/:id - returns a profile for the user id 

### Space

1. /api/space - returns a list of spaces
2. /api/space/:id - returns a space matching the space id 

### Notification

1. /api/notification - returns a list of unseeen notifications
2. /api/notification/:id - returns a list of unseeen notifications for the user id 

### Post

1. GET /api/post - returns a list of posts - optional parameter `eager=true` will return eager data relations including user and comments. optional paramter `space_id` will limit results to the particular space
2. GET /api/post/:id - returns a post matching the post id, will include user and comments
3. DELETE /api/post/:id - deletes a post matching the post id
4. PATCH, PUT /api/post/:id - Updates the specified post.  Accepts JSON body as {"message": "Your Text Here"}
5. POST /api/post - Creates a post. Only valid for Spaces. Uses underlying code to create post, so needs some non-obvious data. visibility must always = 0 and the containerClass as noted. containerGuid is the space's guid. Accepts JSON body as 

 ```json
 {
    "containerClass": "humhub\\modules\\space\\models\\Space",
    "message": "test",
    "user_id": 1,
    "containerGuid": "5bfc6f65-f710-4eab-b488-dfa4e3dc551b",
    "visibility": 0
 }
```

### Comment

1. GET /api/comment - returns a list of comments
2. GET /api/comment/:id - returns a comment matching the comment id 
3. GET /api/comment/post/:id - returns a list of comments for a particular post id
4. DELETE /api/comment/:id - deletes a comment matching the comment id
5. PATCH, PUT /api/comment/:id - Updates the specified comment.  Accepts JSON body as {"message": "Your Text Here"}
6. POST /api/comment - Creates a comment. Only valid for Posts. Accepts JSON body as {"message": "Your Text Here", "post_id": value, "user_id": value}

## Installation
1. Download the module and upload it to your modules directory >yourdomain.com>protected>modules
2. Rename module directory ```api``` (May not be required!)
3. Enable module from >Admin>Modules

## Requirements
This Module requires pretty urls to be enabled - see: https://www.humhub.org/docs/guide-admin-installation.html#4-fine-tuning

## Known Issues:
1. Cannot update a public post.  Will investigate after rest of data is complete.
2. Adding posts is a bit ugly. The underlying code expects Ajax and has some post variables we need to duplicate

## TODO
See Issues for list of TO-DO's


## Authors/Module Website

__Module website:__ <https://github.com/petersmithca/humhub-api>  

__Author website:__ [https://github.com/petersmithca](https://github.com/petersmithca)    
__Author:__ petersmithca    
