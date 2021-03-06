/* 
**  mod_location.c -- Apache httpd location module

**    # Sample apache2.conf configuration
**    LoadModule location_module modules/mod_location.so
**    <Location /location>
**         name "localhost"
**         link "http://localhost/location/"
**         tags "a b c"
**         glat "0"
**         glon "0"
**         grad "0"
**         vote "0"
**         rack "0"
**         SetHandler location
**    </Location>
**
**  Then after restarting Apache via
**
**    $ apachectl restart
**
**  you immediately can request the URL /location/ and watch for the
**  output of this module. This can be achieved for instance via:
**
**    $ lynx -mime_header http://localhost/location/
**
**  The output should be similar to the following one:
**
**    HTTP/1.1 200 OK
**    Date: Wed, 03 Sep 2014 14:28:42 GMT
**    Server: Apache/2.4.7 (Ubuntu)
**    Vary: Accept-Encoding
**    Content-Length: 165
**    Connection: close
**    Content-Type: text/html
**    
**    Location-name: "location"
**    Location-link: "http://localhost/location/"
**    Location-glat: "0"
**    Location-glon: "0"
**    Location-grad: "0"
**    Location-vote: "0"
**    Location-rack: "0"
*/ 

#include "httpd.h"
#include "http_config.h"
#include "http_protocol.h"
#include "ap_config.h"
#include "location.h"

/* The sample content handler */
static int location_handler(request_rec *r)
{
    if (strcmp(r->handler, "location")) {
        return DECLINED;
    }
    r->content_type = "text/html";      

    if (!r->header_only) {
      ap_rputs("Location-name: \"location\"\n", r);
      ap_rputs("Location-link: \"http://localhost/location/\"\n", r);
      ap_rputs("Location-glat: \"0\"\n", r);
      ap_rputs("Location-glon: \"0\"\n", r);
      ap_rputs("Location-grad: \"0\"\n", r);
      ap_rputs("Location-vote: \"0\"\n", r);
      ap_rputs("Location-rack: \"0\"\n", r);
    }
    return OK;
}

static void location_register_hooks(apr_pool_t *p)
{
    ap_hook_handler(location_handler, NULL, NULL, APR_HOOK_MIDDLE);
}

/* Dispatch list for API hooks */
module AP_MODULE_DECLARE_DATA location_module = {
    STANDARD20_MODULE_STUFF, 
    NULL,                  /* create per-dir    config structures */
    NULL,                  /* merge  per-dir    config structures */
    NULL,                  /* create per-server config structures */
    NULL,                  /* merge  per-server config structures */
    NULL,                  /* table of config file commands       */
    location_register_hooks  /* register hooks                      */
};

