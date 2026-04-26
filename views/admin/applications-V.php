<h1>Dossiers</h1>

<table>
<?php foreach ($apps as $a): ?>
<tr>
    <td><?= e($a['vehicle_name']) ?></td>
    <td><?= e($a['name']) ?></td>
    <td><?= e($a['status']) ?></td>

    <td>
        <a href="/index.php?page=admin_accept&id=<?= $a['id'] ?>">Accepter</a>
        <a href="/index.php?page=admin_refuse&id=<?= $a['id'] ?>">Refuser</a>
    </td>
</tr>
<?php endforeach; ?>
</table>