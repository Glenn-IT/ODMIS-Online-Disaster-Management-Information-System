# PhilSMS API v3 Integration Reference

**Endpoint:** `https://dashboard.philsms.com/api/v3/`  
**Authentication:** Bearer token configured in `config/env.php` (`PHILSMS_API_TOKEN`)

---

## 1. Send SMS
- **URL:** `POST https://dashboard.philsms.com/api/v3/sms/send`
- **Headers:**
  - `Authorization: Bearer {PHILSMS_API_TOKEN}`
  - `Accept: application/json`
  - `Content-Type: application/json`
- **Payload:**
  ```json
  {
    "recipient": "09XXXXXXXXX",
    "sender_id": "PhilSMS",
    "type": "plain",
    "message": "Emergency Alert: Typhoon warning for Santo Niño, Cagayan."
  }
  ```

---

## 2. Check Balance
- **URL:** `GET https://dashboard.philsms.com/api/v3/balance`
- **Headers:**
  - `Authorization: Bearer {PHILSMS_API_TOKEN}`
  - `Accept: application/json`
- **Response:**
  ```json
  {
    "status": "success",
    "message": null,
    "data": {
      "remaining_balance": "₱427",
      "expired_on": "29th Jan 27, 19:24"
    }
  }
  ```

---

## 3. Contacts API
- **Base Endpoint:** `https://dashboard.philsms.com/api/v3/contacts`
- Store contact: `POST /api/v3/contacts/{group_id}/store`
- Search contact: `POST /api/v3/contacts/{group_id}/search/{uid}`
- Update contact: `PATCH /api/v3/contacts/{group_id}/update/{uid}`
- Delete contact: `DELETE /api/v3/contacts/{group_id}/delete/{uid}`
