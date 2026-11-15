const http = require('http');

const urls = [
  'http://localhost/wp4/product-category/major-appliances/',
  'http://localhost/wp4/product-category/refrigerators-freezers/',
  'http://localhost/wp4/product-category/washing-machines/',
  'http://localhost/wp4/product-category/electric-cooking/',
  'http://localhost/wp4/product-category/microwaves/',
  'http://localhost/wp4/product-category/food-preparation/',
  'http://localhost/wp4/product-category/beverage-breakfast/',
  'http://localhost/wp4/product-category/cleaning-ironing/',
  'http://localhost/wp4/product-category/cooling-heating-air/',
  'http://localhost/wp4/product-category/tv-audio/',
  'http://localhost/wp4/product-category/personal-care-appliances/'
];

async function checkUrl(url) {
  return new Promise((resolve) => {
    http.get(url, (res) => {
      resolve({ url, status: res.statusCode, location: res.headers.location });
    }).on('error', (err) => {
      resolve({ url, status: 'ERROR', error: err.message });
    });
  });
}

async function run() {
  console.log('Testing category archives:');
  for (const u of urls) {
    const res = await checkUrl(u);
    console.log(`[HTTP ${res.status}] ${res.url}` + (res.location ? ` -> ${res.location}` : ''));
  }
}

run();
