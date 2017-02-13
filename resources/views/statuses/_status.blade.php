<li id="status-{{ $status->id }}">
  <a href="{{ route('users.show', $user->id )}}">
    <img src="http://wx.qlogo.cn/mmopen/sGyfZt1iauRSxuJhpZGcHtqqCE31nWiafzkRvPzTfCcpWKjct0KT8ty3WLcO4Taia8TXibSK1KXR8QmOIssO37cu1POx3YZuFaiaQ/0" alt="{{ $user->name }}" class="gravatar"/>
  </a>
  <span class="user">
    <a href="{{ route('users.show', $user->id )}}">{{ $user->name }}</a>
  </span>
  <span class="timestamp">
    {{ $status->created_at->diffForHumans() }}
  </span>
  <span class="content">{{ $status->content }}</span>
  @can('destroy', $status)
    <form action="{{ route('statuses.destroy', $status->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-sm btn-danger status-delete-btn">删除</button>
      </form>
  @endcan
</li>
