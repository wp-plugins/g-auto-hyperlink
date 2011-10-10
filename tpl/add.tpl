<div class="wrap">
	<div class="icon32" id="icon-glink"><br/></div>
	<h2>
		G Auto-Hyperlink (Add Entry)
	</h2>
	
	{{action-msg}}
	<form id="g-auto-hyperlink-form-add" action="#" method="post">
	<table class="form-table" id="auto-hyperlink-add">
	<tr>
		<th><label for="keyword">Keyword *</label></th>
		<td>
			<input type="text" name="keyword" id="keyword" placeholder="Enter the Keyword" />
			<p class="keyword_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th><label for="url">URL *</label></th>
		<td>
			<input type="text" name="url" id="url" placeholder="Enter the URL" />
			<p class="url_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th><label for="title">Title *</label></th>
		<td>
			<input type="text" name="title" id="title" placeholder="Enter the Title" />
			<p class="title_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th><label for="rel">Rel</label></th>
		<td>
			<select name="rel" id="rel">
				<option></option>
				<option value="external">external</option>
				<option value="nofollow">nofollow</option>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="target">Target</label></th>
		<td>
			<select name="target" id="target">
				<option></option>
				<option value="self">_self</option>
				<option value="blank">_blank</option>
				<option value="top">_top</option>
				<option value="parent">_parent</option>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="appearance">Appearance *</label></th>
		<td>
			<select name="appearance" id="appearance">
				<option value="0">Please Choose</option>
				<option value="spec_cat">Specific Category</option>
				<option value="spec_post">Specific Post</option>
				<option value="spec_page">Specific Page</option>
				<option value="all_post">All Posts</option>
				<option value="all_page">All Pages</option>
				<option value="all_post_and_page">All Posts and Pages</option>
			</select> &nbsp; 			
			{{category-opt}}
			
			<input type="text" id="spec_post_or_page" name="spec_post_or_page" placeholder="Enter URL" />
			
			<p class="appearance_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td><input type="submit" class="button-primary" value="Add Now!" /></td>
	</tr>
	</table>
	</form>
</div>