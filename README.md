# Pagubris API

# API Documentation

-   ## Register User

    Create new user account.

    -   **URL**

        https://api.pagubris.my.id/v1/auth/register

    -   **Method**

        `POST`

    -   **Data Params**

        | Field                 | Type   | Required | Explanation                                            |
        | --------------------- | ------ | -------- | ------------------------------------------------------ |
        | email                 | string | Yes      | Email (unique)                                         |
        | username              | string | Yes      | Username (unique)                                      |
        | first_name            | string | Yes      | First name                                             |
        | last_name             | string | Yes      | Last Name                                              |
        | gender                | string | Yes      | Gender. Choose one: <br> `MALE` \| `FEMALE` \| `OTHER` |
        | password              | string | Yes      | New Password                                           |
        | password_confirmation | string | Yes      | New password confirmation                              |

    -   **Success Response**

        -   **Code:** 200

            **Content**:

            <pre>
            {
                "access_token": "<i>access_token</i>",
                "token_type": "Bearer",
                "expires_in": <i>token_expires_in_seconds</i>,
                "user": {
                    "username": "<i>username</i>",
                    "name": "<i>full_name</i>",
                    "avatar": "<i>avatar_link_or_null</i>"
                }
            }
            </pre>

    -   **Error Response**

        -   **Validation Error**

            **Code:** 422

            **Content**:

            <pre>
            {
                "[error_field]": "[<i>error_info</i>]",
            }
            </pre>

# License

---

Website Pagubris adalah perangkat lunak sumber terbuka di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).