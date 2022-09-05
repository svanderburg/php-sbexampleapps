<p>
This page displays various kinds of HTML elements to test the layout.
</p>

<h2>Some text</h2>

<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
</p>

<pre>
$application = new Application(
    "Test application",

    /* CSS stylesheets */
    array("default.css"),

    /* Sections */
    array(
        "header" => new StaticSection("header.php"),
        "menu" => new MenuSection(0),
        "contents" => new ContentsSection(true)
    ),

    /* Pages */
    new StaticContentPage("Home", new Contents("home.php"))
);
</pre>

<p>A <a href="https://www.brainyquote.com/quotes/quotes/w/williamsha109527.html">quote</a> from William Shakespeare:</p>

<blockquote>
There is nothing either good or bad but thinking makes it so.
</blockquote>

<h2>List tests</h2>

<h3>Unordered list</h3>

<p>This is a unordered list:</p>

<ul>
<li>First item</li>
<li>Second item</li>
<li>Third item</li>
</ul>

<h3>Ordered list</h3>

<p>This is a ordered list:</p>

<ol>
<li>First item</li>
<li>Second item</li>
<li>Third item</li>
</ol>

<h2>Table</h2>

<h3>Table example 1</h3>

<table>
	<tr>
		<th>Column 1</th>
		<th>Column 2</th>
		<th>Column 3</th>
	</tr>
	<tr>
		<td>1</td>
		<td>2</td>
		<td>3</td>
	</tr>
	<tr>
		<td>3</td>
		<td>2</td>
		<td>1</td>
	</tr>
	<tr>
		<td>1</td>
		<td>2</td>
		<td>3</td>
	</tr>
	<tr>
		<td>3</td>
		<td>2</td>
		<td>1</td>
	</tr>
</table>

<h3>Table example 2</h3>

<table>
	<tr>
		<th>Row 1</th>
		<td>Value 1</td>
	</tr>
	<tr>
		<th>Row 2</th>
		<td>Value 2</td>
	</tr>
	<tr>
		<th>Row 3</th>
		<td>Value 3</td>
	</tr>
</table>

<h3>Table example 3</h3>

<div class="tablewrapper">
	<table>
		<tr>
			<th>Column 1</th>
			<th>Column 2</th>
			<th>Column 3</th>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
		<tr>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
			<td>This_is_a_very_long_sentence_that_probably_does_not_fit_well</td>
		</tr>
	</table>
</div>

<h3>Table example 4</h3>

<div class="tablewrapper">
	<table>
		<tr>
			<th>Column 1</th>
			<th>Column 2</th>
			<th>Column 3</th>
			<th>Column 4</th>
			<th>Column 5</th>
			<th>Column 6</th>
			<th>Column 7</th>
			<th>Column 8</th>
			<th>Column 9</th>
			<th>Column 10</th>
		</tr>
		<tr>
			<td>Value 1</td>
			<td>Value 2</td>
			<td>Value 3</td>
			<td>Value 4</td>
			<td>Value 5</td>
			<td>Value 6</td>
			<td>Value 7</td>
			<td>Value 8</td>
			<td>Value 9</td>
			<td>Value 10</td>
		</tr>
		<tr>
			<td>Value 1</td>
			<td>Value 2</td>
			<td>Value 3</td>
			<td>Value 4</td>
			<td>Value 5</td>
			<td>Value 6</td>
			<td>Value 7</td>
			<td>Value 8</td>
			<td>Value 9</td>
			<td>Value 10</td>
		</tr>
		<tr>
			<td>Value 1</td>
			<td>Value 2</td>
			<td>Value 3</td>
			<td>Value 4</td>
			<td>Value 5</td>
			<td>Value 6</td>
			<td>Value 7</td>
			<td>Value 8</td>
			<td>Value 9</td>
			<td>Value 10</td>
		</tr>
		<tr>
			<td>Value 1</td>
			<td>Value 2</td>
			<td>Value 3</td>
			<td>Value 4</td>
			<td>Value 5</td>
			<td>Value 6</td>
			<td>Value 7</td>
			<td>Value 8</td>
			<td>Value 9</td>
			<td>Value 10</td>
		</tr>
	</table>
</div>
