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