<html>
  <head>
    <title>Piperpal</title>
    <!--[if IE]><![endif]-->
    <meta charset="iso-8859-1">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <!--[if IE 8]>
      <link href="common_o2.1_ie8-91981ea8f3932c01fab677a25d869e49.css" media="all" rel="stylesheet" type="text/css" />
    <![endif]-->
   <!--[if !(IE 8)]><!-->
      <link href="common_o2.1-858f47868a8d0e12dfa7eb60fa84fb17.css" media="all" rel="stylesheet" type="text/css" />
    <!--<![endif]-->
    <!--[if lt IE 9]>
      <link href="airglyphs-ie8-9f053f042e0a4f621cbc0cd75a0a520c.css" media="all" rel="stylesheet" type="text/css" />
    <![endif]-->
    <link href="main-f3fcc4027aaa2c83f08a1d51ae189e3b.css" media="screen" rel="stylesheet" type="text/css" />
  <!--[if IE 7]>
    <link href="p1_ie_7-0ab7be89d3999d751ac0e89c44a0ab50.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 6]>
    <link href="p1_ie_6-7d6a1fd8fe9fdf1ce357f6b864c83979.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!-- FIXME
  <script type="text/javascript" src="http://www.piperpal.com/piperpal-autocomplete-tours.php"></script>
  -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
   $(function() {
       $( "#simple-search-tourin" ).datepicker();
       $( "#simple-search-tourout" ).datepicker();
     });
  </script>
  </head>
  <body>
    <img src="http://www.piperpal.com/Logo.png" />
    <form id="searchbar-form" action="s">
    <label for="simple-search-location" class="screen-reader-only">
      What do you describe?
    </label>
    <input type="text"
           placeholder="Location Tag?"
           autocomplete="on"
           name="location"
           id="simple-search-location"
           class="input-large js-search-location"
	   value="<?php echo $_GET['location']; ?>">
    <label for="simple-search-location" class="screen-reader-only">
      What do you provide?
    </label>
    <input type="text"
           placeholder="Location Service?"
           autocomplete="on"
           name="service"
           id="simple-search-service"
           class="input-large js-search-service"
	   value="<?php echo $_GET['service']; ?>">
    <div class="row row-condensed space-top-2 space-2">
      <div class="col-sm-6">
        <label for="simple-search-tourin" class="screen-reader-only">
          Tour in
        </label>
        <input
          id="simple-search-tourin"
          type="text"
          name="notBefore"
          class="input-large tourin js-search-tourin"
          placeholder="Not Before">
      </div>
      <div class="col-sm-6">
        <label for="simple-search-tourout" class="screen-reader-only">
          Tour out
          </label>
        <input
          id="simple-search-tourout"
          type="text"
          name="notAfter"
          class="input-large tourout js-search-tourout"
          placeholder="Not After">
      </div>
    </div>

    <div class="select select-block space-2">
      <label for="simple-search-price" class="screen-reader-only">
        Price
      </label>
      <select id="simple-search-price" name="paid" class="js-search-price">
        <option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-large btn-block">
      Search
    </button>
    </form>
  </body>
</html>
