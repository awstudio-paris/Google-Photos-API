# Google-Photos-API
Inspired by [oc-GooglePhotos-plugin](https://github.com/inetis-ch/oc-GooglePhotos-plugin)
developed by [AWStudio](http://www.awstudio.es)

## Description
This library provides an interface to access to google photos using [Picasa API](https://developers.google.com/gdata/docs/2.0/reference). The Picasa API is deprecated, you should only use this client if you want to get Album information, if you don't care about it, you should use the Google Drive API to get the photos.

## Instalation
You can use composer to install it:
```
composer require awstudio/google-photos-api
```

## Usage
To use the client you will need a Google Access Token. Check it [here](#access_token) to know how to get it.

```php
<?php

use GooglePhotosApi\Client\GooglePhotosClient;  
  
[...] 
  

$googlePhotosClient = new GooglePhotosClient($settings);
$googlePhotosClient->setGoogleAccessToken($googleAccessToken);
  
$albums = $googlePhotosClient->getAlbumsList();
  
$photos = [];  
  
foreach ($albums as $album){
    $albumId = $album->getAlbumId();
    $photos[$albumId] = $googlePhotosClient->getAlbumImages($albumId);
}

```

## Settings
You can adjust some settings of the client either using the constructor or with the method setSettings. The settings are an array that has as key the value that you want to override and as value the modification.


| Setting        | Type                                          | Default | Description                                     |
|----------------|-----------------------------------------------|---------|-------------------------------------------------|
| kind           | String (album / photo / comment / tag / user) | album   |                                                 |
| visibility     | String (all / private / public / visible)     | all     |                                                 |
| thumb_size     | int                                           | 200     | Size of the thumbnails that will be generated   |
| crop_mode      | String (h / w / s)                            | s       |                                                 |
| should_crop    | bool                                          | false   |                                                 |
| max-results    | int                                           | null    | Max results to show by petition.                |
| start-index    | int                                           | null    | Combine with max-result to apply pagination     |
| ignored_albums | array                                         | []      | albums that want to be ignored, by title or ID  |
| ignore_videos  | bool                                          | true    | Set to false to get the videos on the response. |

For more info check [Picasa API](https://developers.google.com/gdata/docs/2.0/reference)


## <a name="access_token"></a> Google Access Token
We don't provide a way to get the Google Access Token. Please check the [official documentation](https://support.google.com/googleapi/answer/6158857) for that or use a third party library that provides OAuth2 authentification. Right now we use [HWIOAuthBundle](https://github.com/hwi/HWIOAuthBundle) for our Symfony projects.