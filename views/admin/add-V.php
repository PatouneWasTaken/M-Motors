<form method="POST">
    <input name="name" placeholder="Marque">
    <input name="brand" placeholder="Model">

    <select name="type">
    	<option value="sale">Vente</option>
    	<option value="rent">Location</option>
    </select>

    <input name="price" type="number">

	<textarea name="description" placeholder="description"></textarea>

	<input type="file" name="image" accept="image/*" onchange="previewImage(event)">
	<img id="preview" style="max-width:200px; display:none;">

    <button>Ajouter</button>
</form>

<script>
	function previewImage(event) {
    	const img = document.getElementById('preview');
    	img.src = URL.createObjectURL(event.target.files[0]);
    	img.style.display = 'block';
	}
</script>