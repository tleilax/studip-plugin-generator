<fieldset data-id="icon">
	<legend>
		<img src="assets/images/plus.png" alt="">
		Icon hinzufügen
	</legend>
	
	<div class="text">
		<label for="icon">Icon-Datei</label>
		<input type="file" name="file" id="file" accept="image/*">
	</div>
	
	<div class="checkbox">
		<label for="create-icon">
		    Sprite erzeugen
		    <dfn>
		        
		    </dfn>
		</label>
		<label/>
		<input type="checkbox" name="create-icon" id="create-icon" value="1" <?= request('create-icon') ? 'checked' : '' ?>>
	</div>
</fieldset>
