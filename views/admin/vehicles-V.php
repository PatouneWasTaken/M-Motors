<h1>Véhicules</h1>

<section>

	<a href="/index.php?page=admin_add_vehicle">Ajouter</a>

</section>

<section>

<?php foreach ($vehicles as $v): ?>

	<article>

    	<p>
			<?= e($v['name']) ?>
		</p>

    	<p>
			<?= e($v['brand']) ?>
		</p>

    	<p>
			<?= e($v['type']) ?>
		</p>

    	<p>
        	<a href="/index.php?page=admin_edit_vehicle&id=<?= $v['id'] ?>">Edit</a>
        	<a href="/index.php?page=admin_delete_vehicle&id=<?= $v['id'] ?>">Delete</a>
		</p>

	</article>

<?php endforeach; ?>

</section>



<!-- 
<h1>Véhicules</h1>

<a href="/index.php?page=admin_add_vehicle">Ajouter</a>

<table>
<?php foreach ($vehicles as $v): ?>
<tr>
    <td><?= e($v['name']) ?></td>
    <td><?= e($v['brand']) ?></td>
    <td><?= e($v['type']) ?></td>

    <td>
        <a href="/index.php?page=admin_edit_vehicle&id=<?= $v['id'] ?>">Edit</a>
        <a href="/index.php?page=admin_delete_vehicle&id=<?= $v['id'] ?>">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
-->