<table>
    <thead>
        <tr>
            <th> Author </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lists as $list)
            <tr>
                <td>{{ $list->author }}</td>
            </tr>
        @endforeach
    </tbody>
</table>