<table>
    <thead>
        <tr>
            <th> Title </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lists as $list)
            <tr>
                <td>{{ $list->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>