<li>
  <img src="http://wx.qlogo.cn/mmopen/sGyfZt1iauRSxuJhpZGcHtqqCE31nWiafzkRvPzTfCcpWKjct0KT8ty3WLcO4Taia8TXibSK1KXR8QmOIssO37cu1POx3YZuFaiaQ/0" alt="{{ $user->name }}" class="gravatar"/>
  <a href="{{ route('users.show', $user->id )}}" class="username">{{ $user->name }}</a>

  @can('destroy', $user)
  <form action="{{ route('users.destroy', $user->id) }}" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-sm btn-danger delete-btn">删除</button>
      </form>
  @endcan
</li>
