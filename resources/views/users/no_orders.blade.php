@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh sách người dùng</h1>

        <form method="GET" action="{{ route('users.no_orders') }}" style="margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tên hoặc email"
                    style="padding: 5px; width: 250px;">
                <button type="submit"
                    style="padding: 5px 10px; background-color: #3490dc; color: white; border: none; cursor: pointer;">
                    Tìm
                </button>
            </div>
        </form>
        {{-- filter dropdown --}}
        <div style="display: flex; justify-content:left; position: relative; margin-bottom: 20px; align-items:">
            <button onclick="toggleFilterDropdown()"
                style="padding: 8px 12px; background-color: #6c757d; color: white; border: none; cursor: pointer;">
                Bộ lọc
            </button>
        
            <div id="userFilterDropdown" style="display: none; position: absolute; top: 110%; left: 0; background: white; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.15); z-index: 100; width: 280px;">
                <a href="{{ route('users.top_buy_time') }}" style="display: block; padding: 10px; text-decoration: none; color: black;">User mua hàng có số lượng nhiều nhất</a>
                <a href="{{ route('users.top_spend') }}" style="display: block; padding: 10px; text-decoration: none; color: black;">User mua hàng có giá trị lớn nhất</a>
                <a href="{{ route('users.no_orders') }}" style="display: block; padding: 10px; text-decoration: none; color: black;">User không mua hàng</a>
            </div>
        </div>
        
        <script>
            function toggleFilterDropdown() {
                const dropdown = document.getElementById('userFilterDropdown');
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('userFilterDropdown');
                if (!event.target.closest('button') && !event.target.closest('#userFilterDropdown')) {
                    dropdown.style.display = 'none';
                }
            });
        </script>
        <table>
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Email</th>
                    <th>Tổng tiền mua</th>
                    <th>Lệnh</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>${{ $user->total_spent ?? 0 }}</td>
                        <td>
                            <button class="index-button"
                                style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                onclick="window.location.href='{{ route('users.show', $user->id) }}'">Xem</button>
                            @can('update', auth()->user())
                                <button class="index-button"
                                    style="background-color: blue; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                    onclick="window.location.href='{{ route('users.edit', $user->id) }}'">Sửa</button>
                            @endcan
                            @can('update', auth()->user())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="index-button"
                                        style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <!-- Pagination -->
        {{ $users->links() }}
    </div>
@endsection
