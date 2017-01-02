# TinyJWT
**Notice: This is actually not a strict JWT solution, but by following JWT thinkings.** 

Differences are listed here:
* Comparing to classic `JWT`, it does't have `header` part. So the result token contains only two parts which are divided by "."
* It does't use `openssl` solution (so you need to install extra library and extension) because of the [reason](https://paragonie.com/blog/2016/12/everything-you-know-about-public-key-encryption-in-php-is-wrong)
* There's no predefined fields (`claims` in JWT standard), you do what you want

## How to install
1. Install the library `libsodium`
2. Install the php extension `ext-libsodium`
3. Install this library `composer require yarco/tinyjwt`

## How to use
I won't tell you! ( :) Actually, I don't have time to write down details. )

## Contact
Mail to yarco.wang@gmail.com is always welcomed.

