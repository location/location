/* 
**  mod_location.c -- Apache httpd location module

**    # Sample apache2.conf configuration
**    LoadModule location_module modules/mod_location.so
**    <Location /location>
**         location_name "localhost"
**         location_tags "a cab ac"
**         location_link "http://localhost/location/"
**         location_glat "0"
**         location_glon "0"
**         location_grad "0"
**         location_vote "0"
**         location_rank "0"
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
**    Location-tags: "a b c"
**    Location-link: "http://localhost/location/"
**    Location-glat: "0"
**    Location-glon: "0"
**    Location-grad: "0"
**    Location-vote: "0"
**    Location-rank: "0"
*/ 

#include "httpd.h"
#include "http_config.h"
#include "http_protocol.h"
#include "ap_config.h"
#include "location.h"

typedef struct {
    char *location_name;
    char *location_tags;
    char *location_link;
    char *location_glat;
    char *location_glon;
    char *location_grad;
    char *location_vote;
    char *location_rank;
} location_config;

static const command_rec location_directives[] = {
  AP_INIT_TAKE1 ("location_name", TAKE1, NULL, RSRC_CONF, "Relative location path, such as 'location'"),
  AP_INIT_TAKE1 ("location_tags", TAKE1, NULL, RSRC_CONF, "Relative location tags, such as 'cab', 'dab'"),
  AP_INIT_TAKE1 ("location_link", TAKE1, NULL, RSRC_CONF, "URL to location path, such as 'http://localhost/location/'"),
  AP_INIT_TAKE1 ("location_glat", TAKE1, NULL, RSRC_CONF, "Location Latitude, such as '62.08372'"),
  AP_INIT_TAKE1 ("location_glon", TAKE1, NULL, RSRC_CONF, "Location Longitude, such as '10.39710'"),
  AP_INIT_TAKE1 ("location_grad", TAKE1, NULL, RSRC_CONF, "Location Radius, such as '50' km"),
  AP_INIT_TAKE1 ("location_vote", TAKE1, NULL, RSRC_CONF, "Location Vote, such as '0' or '1'"),
  AP_INIT_TAKE1 ("location_rank", TAKE1, NULL, RSRC_CONF, "Location Rank, such as '1', '2', or '3'")
};

static void location_register_hooks(apr_pool_t *p);

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

/* The sample content handler */
static int location_handler(request_rec *r)
{
    if (strcmp(r->handler, "location")) {
        return DECLINED;
    }
    location_config *config = (location_config*) ap_get_module_config(r->per_dir_config, &location_module);
    r->content_type = "text/html";      
    if (!r->header_only) {
      ap_rprintf(r, "Location-name: \"%s\"\n", config->location_name);
      ap_rprintf(r, "Location-tags: \"%s\"\n", config->location_tags);
      ap_rprintf(r, "Location-link: \"%s\"\n", config->location_link);
      ap_rprintf(r, "Location-glat: \"%s\"\n", config->location_glat);
      ap_rprintf(r, "Location-glon: \"%s\"\n", config->location_glon);
      ap_rprintf(r, "Location-grad: \"%s\"\n", config->location_grad);
      ap_rprintf(r, "Location-vote: \"%s\"\n", config->location_vote);
      ap_rprintf(r, "Location-rank: \"%s\"\n", config->location_rank);
     }
    return OK;
}

static void location_register_hooks(apr_pool_t *p)
{
    ap_hook_handler(location_handler, NULL, NULL, APR_HOOK_MIDDLE);
}
