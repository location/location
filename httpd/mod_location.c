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

typedef struct {
    CONF_VALUE *name;
    CONF_VALUE *link;
    CONF_VALUE *glat;
    CONF_VALUE *glon;
    CONF_VALUE *grad;
    CONF_VALUE *vote;
    CONF_VALUE *rack;
} location_config;

/* The sample content handler */
static int location_handler(request_rec *r)
{
    if (strcmp(r->handler, "location")) {
        return DECLINED;
    }
    location_config *config = (location_config*) ap_get_module_config(r->per_dir_config, &location_module);
    r->content_type = "text/html";      
    if (!r->header_only) {
      ap_rprintf("Location-name: \"%s\"\n", config->name);
      ap_rprintf("Location-tags: \"%s\"\n", config->tags):
      ap_rprintf("Location-link: \"%s\"\n", config->link);
      ap_rprintf("Location-glat: \"%s\"\n", config->glat);
      ap_rprintf("Location-glon: \"%s\"\n", config->glon);
      ap_rprintf("Location-grad: \"%s\"\n", config->grad);
      ap_rprintf("Location-vote: \"%s\"\n", config->vote);
      ap_rprintf("Location-rack: \"%s\"\n", config->rank);
    }
    return OK;
}

static void location_register_hooks(apr_pool_t *p)
{
    ap_hook_handler(location_handler, NULL, NULL, APR_HOOK_MIDDLE);
}

static const command_rec location_directives[] =
{
 AP_INIT_TAKE1 ("name", TAKE1, NULL, RSRC_CONF, "Relative location path, such as 'location'")
 AP_INIT_TAKE1 ("tags", TAKE1, NULL, RSRC_CONF, "Relative location tags, such as 'cab', 'dab'")
 AP_INIT_TAKE1 ("link", TAKE1, NULL, RSRC_CONF, "URL to location path, such as 'http://localhost/location/'")
 AP_INIT_TAKE1 ("glat", TAKE1, NULL, RSRC_CONF, "Location Latitude, such as '62.08372'")
 AP_INIT_TAKE1 ("glon", TAKE1, NULL, RSRC_CONF, "Location Longitude, such as '10.3971'")
 AP_INIT_TAKE1 ("grad", TAKE1, NULL, RSRC_CONF, "Location Radius, such as '100' km")
 AP_INIT_TAKE1 ("vote", TAKE1, NULL, RSRC_CONF, "Location Vote, such as '1' or '0'")
 AP_INIT_TAKE1 ("rack", TAKE1, NULL, RSRC_CONF, "Location Rack, such as 'K1', 'M1'")
}

/* Dispatch list for API hooks */
module AP_MODULE_DECLARE_DATA location_module = {
    STANDARD20_MODULE_STUFF, 
    NULL,                  /* create per-dir    config structures */
    NULL,                  /* merge  per-dir    config structures */
    NULL,                  /* create per-server config structures */
    NULL,                  /* merge  per-server config structures */
    location_directives,   /* table of config file commands       */
    location_register_hooks  /* register hooks                      */
};

