# premailer
Inline CSS for use in html emails, uses Dialect Preflight API

## install

    composer require paulbunyannet/premailer:"^1.0"

## Usage

### From String

To inline css for a html email from a string:


    <?php
    require_once 'vendor/autoload.php';

    $html = '<html>
            <head>
                <style>
                    .paragraph {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <p class="paragraph">Lorem ipsum dolor sit amet, ne sea sumo laudem. Iracundia concludaturque no pro. Ex tempor praesent eos, ea dicta consetetur ius, eligendi posidonium referrentur cum no. Nulla dissentiet vel et, mei at sumo numquam, pro iriure constituam voluptatibus te. Affert fabulas impedit nec an, aeterno partiendo voluptaria duo ne.</p>
            </body>
         </html>';
         
    $inlined = Premailer::html($html);         

This will result in the .paragraph class attributes being injected into the paragraph tag it's used in.

### From a url

    <?php
    require_once 'vendor/autoload.php';
    $url = 'https://google.com';     
    $inlined = Premailer::url($url);

## Response
Both responses will return keys "html", the html string with inlined css, and "plain", a plain text version of the html provided, on success or exception on error.
         
