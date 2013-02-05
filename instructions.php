<?php if(!defined("PROCESSWIRE")) die('No direct access'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Pages Web Service</title>
        <meta name="robots" content="noindex, nofollow" />

	<style type='text/css'>
		body {
			padding: 20px; 
			font-family: Arial, Helvetica, sans-serif;
		}

		#content {
			max-width: 800px;
			margin: 0 auto;
		}

		.example {
			padding: 10px 20px 20px 20px;
			background: #eee; 
			margin: 1em 0;
		}

		pre {
			background: #eee; 
		}

		h2, h3 {
			padding: 10px 20px; 
			background: #555; 
			color: #fff; 
		}
		h3 {
			background: #999;
		}
	</style>

	<?php $adminTemplates = wire('config')->urls->adminTemplates; ?>

        <script type="text/javascript" src="<?php echo wire('config')->urls->modules; ?>Jquery/JqueryCore/JqueryCore.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#submit1").click(function() {

				var query = $('#query1').val();
				if(query.substring(0, 1) != '?') query = '?' + query;

				$.getJSON('./' + query, function(data) {

					var html = '';

					if(data.errors) {

						$.each(data.errors, function(key, error) {
							html += "<p>Error: " + error + "</p>";
						});

					} else {

						html = 	"<ul>" +
							"<li>Selector: <code>" + data.selector + "</code></li>" + 
							"<li>Total: " + data.total + "</li>" + 
							"<li>Limit: " + data.limit + "</li>" +
							"<li>Start: " + data.start + "</li>" +
							"<li>Matches: " +
							"<ul>";

						$.each(data.matches, function(key, page) {
							html += "<li>" + key + "<ul>";
							$.each(page, function(field, value) {
								html += "<li>" + field + ": " + value + "</li>"; 
							});
							html += "</ul></li>";
						}); 
						html += "</ul></li></ul>";
					}

					$("#output1").html(html);
				}); 
				return false;
			});

			$("#submit2").click(function() {
				var query = $("#query2").val();
				$.getJSON('./?title%=' + query, function(data) {
					var html = '';
					if(data.errors) {
						$.each(data.errors, function(key, error) {
							html += "<p>Error: " + error + "</p>";
						});

					} else if(data.total == 0) {
						html = "No results found";

					} else {
						html = "<strong>Search results for " + query + "</strong><ul>";
						$.each(data.matches, function(key, page) {
							html += "<li>" + page.path + "</li>";	
						}); 
						html += "</ul>";
					}
					$("#output2").html(html);
				}); 
				return false;
			}); 
		}); 
	</script>

</head>
<body>

<div id="content">

	<h1>ProcessWire Pages Web Service Instructions</h1>

	<p>These instructions are being shown because no query was specified in the URL.</p>
	<p>You are welcome to move this page wherever you want to in your site structure.</p>

	<h2>Examples</h2>

	<div class='example'>

		<pre id='output2'></pre>

		<form>
			<label>Type a keyword to search <em>title</em> for:</label>
			<input type='text' size='50' id='query2' value=''>
			<button id='submit2'>Submit</button><br />
			<small>This only works if you have <em>title</em> defined as an allowed query field in the module settings.</small>
		</form>

	</div>

	<div class='example'>

		<pre id='output1'></pre>

		<form>
			<label>Type a URL query string here to test:</label>
			<input type='text' size='50' id='query1' value='template=basic-page&sort=-modified'>
			<button id='submit1'>Submit</button>
		</form>

	</div>



	<h2>Input</h2>
	<p>
	This page can be queried with GET variables in the URL to return JSON-format results. 
	The query string should follow a <a href='http://processwire.com/api/selectors/'>ProcessWire selector format</a> 
	([field][operator][value]), but modified a bit for use in a URL query string. 
	Here are a few format examples:
	</p>

	<ul>
	<li>
	<p>
	Specify a single value:<br />
	<code>?field=value</code> 
	</p>
	</li>

	<li>
	<p>
	Specify multiple fields and values to match:<br />
	<code>?field1=value1&field2=value2&field3=value3</code> 
	</p>
	</li>

	<li>
	<p>
	Specify multiple fields where at least one must match the value. Note use of "," rather than "|", something we had to settle for to make it work as a URL key:<br />
	<code>?field1,field2,field3=value</code> 
	</p>
	</li>

	<li>
	<p>
	Specify one field with multiple possible values (it's fine to use "|" as a separator here):<br />
	<code>?field=value1|value2|value3</code> 
	</p>
	</li>

	</ul>

	<p>The selector/query string examples above would be appended to this web service URL, i.e.<br /><code><?php echo $page->url; ?>?field=value</code></p>

	<p><strong>Note that unlike regular ProcessWire selectors, multiple field=value sets are split with an ampersand "&amp;" rather than a comma ",".</strong></p> 

	<h3>Allowed fields</h3>
	<p>The allowed values for <em>field</em> are set with the module configuration. The current configuration allows the following fields to be queried:</p>
	<ul>
	<?php foreach($queryFields as $value) echo "<li>" . (ctype_digit("$value") ? wire('fields')->get($value) : $value) ."</li>"; ?>
	</ul>
	<p>You may also specify the following modifier keyword=value pairs:</p>
	<ul>
	<li><code>sort=[field]</code> (Specify field name to sort results by)</li>
	<li><code>debug=1</code> (Enables debug mode producing human readable output)</li>
	<li><code>limit=[n]</code> (Specify the max number of pages to return)</li>
	<li><code>start=[n]</code> (Specify the result number to start with)</li>
	<li><code>include=hidden</code> (Include pages that are 'hidden')</li>
	</ul>

	<h3>Allowed operators</h3>
	<p>The operator demonstrated by the "=" sign in the examples above may be replaced with any of the following operators in the query string:</p>
	<pre>
   =   Equal to
   !=  Not equal to
   <   Less than
   >   Greater than
   <=  Less than or equal to
   >=  Greater than or equal to
   *=  Contains the exact word or phrase
   ~=  Contains all the words 
   %=  Contains the exact word or phrase (using slower SQL LIKE) 
   ^=  Contains the exact word or phrase at the beginning of the field 
   $=  Contains the exact word or phrase at the end of the field </pre>

	<p>As an example, this ProcessWire selector:<br />
	<code>    template=property, body*=luxury, bedrooms>5, bathrooms<=3</code></p>

	<p>...would be specified as a query string to this web service like this:<br />
	<code>    ?template=property&body*=luxury&bedrooms>5&bathrooms<=3</code></p>
		
		<h3>Allowed templates</h3>	
		<p>By default, the search will be performed on pages using the following templates (as specified in the module configuration):</p>
		<ul>
		<?php foreach($queryTemplates as $value) echo "<li>" . wire('templates')->get($value) . "</li>"; ?>
		</ul>
		<p>You may add more templates to this list by editing the ServicesPages module configuration.</p>
		<p>If <em>template</em> is one of your allowed query fields, then you may reduce the above by specifying the template(s) to query in the selector: <code>template=name</code></p>

		<h2>Output</h2>
		<p>The returned value is a JSON format string in the following format (populated with example values):
	<pre>
	{
	    selector: "title*=something, template=basic-page, limit=50",
	    total: 2,
	    limit: 50,
	    start: 0,
	    matches: [
		{
		    id: 1002,
		    parent_id: 4525,
		    template: "basic-page",
		    path: "/test/hello/",
		    name: "hello"
		}, 
		{
		    id: 1005,
		    parent_id: 4525,
		    template: "basic-page",
		    path: "/test/contact/",
		    name: "Contact Us"
		}
	    ]
	}
	</pre>

		<p>Each of the <em>matches</em> values will also include all the fields you have specified to appear with the ServicePages module configuration.</p>

		<p>If an error in the query prevented it from being performed, a JSON string in this format will be returned:</p> 
	<pre>
	{
	    errors: [
		"Error message 1",
		"Error message 2 (if there was one)",
		"And so on..."
	    ]
	}
	</pre>

	<p>The web service honors user view permissions. As a result, if you are accessing this service from a superuser account, you are likely to get
	pages that others users may not see. Superusers get an "include=all" automatically, unless you override it with an "include=hidden".</p>

	<h3>Returned field values</h3>

	<p>The following field values will be returned for all matched pages:</p>
	<ul>
	<li>id (integer)</li>
	<li>parent_id (integer)</li>
	<li>template (string)</li>
	<li>path (string)</li>
	<li>name (string)</li>
	</ul>

	<p>In addition, your module configuration also specifies that the following fields will be included:</p>
	<ul>
	<?php foreach($displayFields as $value) echo "<li>" . (ctype_digit("$value") ? wire('fields')->get($value) : $value) . "</li>"; ?>
	</ul>
	<p>You may add more by editing the ServicePages module configuration.</p>
	

	<h3>Pagination</h3>

	<p>To paginate, simplify add a "page[n]" url segment to the request URL, i.e.<br />
	<code><?php echo $page->url; ?>page2/?template=basic-page&sort=name</code>


	<hr />
	<p><small>Copyright 2012 by Ryan Cramer</small></p>


</div><!--/content-->


</body>
</html>

