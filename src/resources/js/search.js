const searchBtn = document.getElementById('js-search-btn');
const selectBtn = document.getElementById('js-select-btn');
const searchInput = document.getElementById('search');

searchBtn.addEventListener('click', (e) => {
  e.preventDefault();
  const category = selectBtn.value;
  const value = searchInput.value;
  const queryParameter = `?search=${category}&value=${value}`;
  location.href = `/${queryParameter}`;
});
