# Organization Methods

[Back to Pixelbin API docs](./README.md)

Organization Service

* [getAppOrgDetails](#getapporgdetails)

## Methods with example and description

### getAppOrgDetails

**Summary**: Get App Details

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->organization->getAppOrgDetails(
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

Get App and org details

*Returned Response:*

[AppOrgDetails](#apporgdetails)

Success. Returns a JSON object as shown below. Refer `AppOrgDetails` for more details.

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "app": {
    "_id": 123,
    "orgId": 12,
    "name": "Desktop Client App",
    "permissions": [
      "read",
      "read_write"
    ],
    "active": false,
    "createdAt": "2021-07-15T07:47:00Z",
    "updatedAt": "2021-07-15T07:47:00Z"
  },
  "org": {
    "_id": 12,
    "name": "org_1",
    "cloudName": "testcloudname",
    "accountType": "individual",
    "industry": "Ecommerce",
    "strength": "1",
    "active": "false"
  }
}
```

</details>

### Schemas

#### OrganizationDetailSchema

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | int |  no  | Id of the organization |
| name | string |  no  | Organization Name |
| cloudName | string |  no  | Unique cloud name associated with the organization |
| ownerId | string |  no  | User Id of the organization owner |
| active | bool |  no  | Whether the organization is active |
| createdAt | string |  no  | Timestamp when the organization was created |
| updatedAt | string |  no  | Timestamp when the organization was last updated |

#### AppSchema

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | int |  no  | App id |
| orgId | int |  no  | Organization id the app belongs to |
| name | string |  no  | Name of the app |
| token | string |  no  | api token for the app |
| permissions | array |  no  | Permissions applied on the app |
| active | bool |  no  | Whether the app is active |
| createdAt | string |  no  | Timestamp when the app was created |
| updatedAt | string |  no  | Timestamp when the app was last updated |

#### AppOrgDetails

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| app | AppSchema |  no  |  |
| org | OrganizationDetailSchema |  no  |  |

#### ErrorSchema

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| message | string |  no  |  |
