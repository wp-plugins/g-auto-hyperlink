<div class="wrap">
	<div class="icon32" id="icon-glink"><br/></div>
	<h2>
		G Auto-Hyperlink (Edit Entry)
	</h2>
	
	{{action-msg}}
	
	<form id="g-auto-hyperlink-form-edit" action="#" method="post">
	<input type="hidden" value="{{ID}}" name="entryid" id="entryid" />
	<table class="form-table" id="auto-hyperlink-add">
	<tr>
		<th><label for="keyword">Keyword *</label></th>
		<td>
			<input type="text" name="keyword" id="keyword" placeholder="Enter the Keyword" value="{{keyword}}" />
			<p class="keyword_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th><label for="url">URL *</label></th>
		<td>
			<input type="text" name="url" id="url" value="{{url}}" placeholder="Enter the URL" />
			<p class="url_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th><label for="title">Title *</label></th>
		<td>
			<input type="text" name="title" id="title" value="{{title}}" placeholder="Enter the Title" />
			<p class="title_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th><label for="rel">Rel</label></th>
		<td>
			<select name="rel" id="rel">
				{{rel}}
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="target">Target</label></th>
		<td>
			<select name="target" id="target">
				{{target}}
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="appearance">Appearance *</label></th>
		<td>
			{{appearance}}
			<p class="appearance_error form_error"></p>
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td><input type="submit" class="button-primary" value="Save" /></td>
	</tr>
	</table>
	</form>
</div>