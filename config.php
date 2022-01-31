<?php
/**
 * Database configuration
 */
define('DB_USERNAME', 'ninicom_merce');
define('DB_PASSWORD', 'NCR$q222120');
define('DB_HOST', 'localhost');
define('DB_NAME', 'ninicom_merce');
// define('DB_NAME', 'amfacili_call2fix');

/* Site URL Config */
define('SITE_URL', '');

/*Mobile URL*/
define('APP_URL', 'https://play.google.com/store');

/* Brand ID */
define('SHORTNAME', 'Nini-Commerce');
define('LONGNAME', 'Nini-Commerce Application');

/*Site Email*/
define('FROM_EMAIL', 'niniola@tulabyte.net');

/*Admin Email*/
define('ADMIN_EMAIL', 'niniola@tulabyte.net');

// /* NORMAL LOCALHOST SMTP */
// /*Server SMTP Access*/
// define('SMTP_SERVER', 'localhost');
// define('SMTP_EMAIL','server@helmetcrest.com');
// define('SMTP_PWD','yemi_1234');
// define('SMTP_PORT', '25');
// define('SMTP_ENCRYPTION', null); // use null if not an ssl server

/* MAILJET SMTP RELAY */
/*Server SMTP Access*/
define('SMTP_SERVER', 'localhost');
define('SMTP_EMAIL','info@helmet-crest.com');
define('SMTP_PWD','yemi1234');
define('SMTP_PORT', '465');
define('SMTP_ENCRYPTION', null); // use null if not an ssl server

// /* GMAIL SMTP RELAY */
// /*Server SMTP Access*/
// define('SMTP_SERVER', 'smtp.gmail.com');
// define('SMTP_EMAIL','yemitula@gmail.com');
// define('SMTP_PWD','ggmail8855');
// define('SMTP_PORT', '465');
// define('SMTP_ENCRYPTION', 'ssl'); // use null if not an ssl server

/*Swiftmailer Auth Type - MAIL/SMTP*/
define('MAILER_TYPE','SMTP');


/*Hash Secret*/
define('FHS', 'rTxNwwoPaq14smONPKdl');

/*JWT Secret*/
define('JWT_KEY', 'fdb!AiNGITT*yt1920@tb.com');

/*Login Token Lifetime*/
define('LOGIN_TOKEN_LIFETIME', '+24 hour');

/*Public URLS Allowed - No Auth*/
// define('PUBLIC_ROUTES', ['\/userLogin', '\/userSignup']);

/* EBULKSMS Parameters */
define('EBULK_USERNAME', 'yemgab@yahoo.com');
define('EBULK_APIKEY', 'a83a9fab2671a6c3159b835d4f2c11d71321f7d8');
define('EBULK_SENDER', 'HelmetCrest');

/*Paystack Secret Keys*/
define('PAYSTACK_TEST_SECRET',"sk_test_9bbfa36a48cc34a2edebfc319509a34fe75aa614");
define('PAYSTACK_LIVE_SECRET',"sk_live_7fa4ecdd753d09c54854dc3e721d768e6791d22a");

