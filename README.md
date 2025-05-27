# H_azericard PHP Class

**Azericard 3D Secure ödəniş sistemi** ilə inteqrasiya üçün hazırlanmış sadə PHP sinfi. Bu sinif vasitəsilə ödəniş forması yarada, `p_sign` imzasını avtomatik hesablaya və istifadəçini ödəniş səhifəsinə yönləndirə bilərsiniz.

## 🔧 Quraşdırma

Faylı layihənizə əlavə edin:  
`H_azericard.php`

## 📦 İstifadə qaydası

```php
require_once 'H_azericard.php';

$azericard = new H_azericard('TERMINAL_ID', 'MERCHANT_NAME');
$azericard->setAmount(10.50);
$azericard->setOrderId('123456');
$azericard->setDescription('Məhsul təsviri');
$azericard->setMerchantUrl('https://yourdomain.com/callback'); // Cavab URL-si
$azericard->setEmail('your@email.com');
$azericard->setBackref('https://yourdomain.com/thank-you'); // İstifadəçi yönləndiriləcək URL
$azericard->setKeyForSign('SHA1_HEX_SECRET'); // Bank tərəfindən verilən gizli açar

$azericard->makeForm();
echo $azericard->getForm(); // HTML forma çıxışı
```

### 💳 Avtomatik göndərilən forma (istəyə bağlı)

```php
$azericard->getJsForm(); // HTML form + JS ilə 1 saniyə sonra avtomatik submit
```

## 📝 Əsas metodlar

| Metod | Təsviri |
|-------|---------|
| `setAmount(float)` | Məbləği AZN olaraq təyin edir |
| `setCurrency(int)` | Valyutanı dəyişmək üçün (default: 944 - AZN) |
| `setOrderId(string)` | Sifariş ID |
| `setDescription(string)` | Ödənişin təsviri |
| `setMerchantUrl(string)` | Callback URL |
| `setEmail(string)` | Müştərinin e-maili |
| `setBackref(string)` | Ödənişdən sonra yönləndiriləcək URL |
| `setKeyForSign(string)` | `p_sign` üçün HMAC SHA1 gizli açarı |
| `makeForm()` | HTML formanı yaradır |
| `getForm()` | Formanı HTML olaraq qaytarır |
| `getJsForm()` | Formanı JS ilə avtomatik göndərir |

## ✅ Nümunə HTML Çıxışı

```html
<form method="POST" action="https://mpi.3dsecure.az/cgi-bin/cgi_link">
  <input type="hidden" name="AMOUNT" value="10.50">
  <input type="hidden" name="ORDER" value="123456">
  ...
  <button type="submit">Pay Now</button>
</form>
```

## 🛡️ Qeyd

- `keyForSign` – Bank tərəfindən sizə təqdim olunan **HEX formatında HMAC SHA1 gizli açar** olmalıdır.
- `CURRENCY` default olaraq 944 (AZN) təyin olunub.
- `TRTYPE`, `MERCH_GMT`, `COUNTRY` və s. dəyişmək üçün uyğun setter metodlar mövcuddur.

## 📄 Lisenziya

Bu layihə MIT lisenziyası ilə yayımlanır.
