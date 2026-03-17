<h1>Lista de Usuarios</h1>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($usuarios as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['nombre'] ?></td>
            <td><?= $user['email'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>