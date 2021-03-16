<ul>
  @foreach ($pictures as $picture)
  <li>
    <p>
      <a href="{{ route('pictures.show', $picture) }}">
        {{ $picture->title }}
        <img src="{{ route('pictures.show', $picture) }}">
      </a>
    </p>
  </li>
  @endforeach
</ul>
