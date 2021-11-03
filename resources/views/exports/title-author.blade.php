<table>
    <thead>
        <tr>
            <th> Title </th>
            <th> Author </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lists as $list)
            <tr>
                <td>{{ $list->title }}</td>
                <td>{{ $list->author }}</td>
            </tr>
        @endforeach
    </tbody>
</table>