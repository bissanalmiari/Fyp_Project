# Unipath Recommendation API

This folder runs the public recommendation API used by Laravel.

## Render Settings

Root directory:

```text
Unipath/mm recom
```

Build command:

```bash
pip install -r requirements.txt
```

Start command:

```bash
python api_server.py
```

Environment variables:

```env
RECOMMENDER_API_HOST=0.0.0.0
RECOMMENDER_API_KEY=choose-a-long-secret-key
```

Render provides `PORT` automatically, and `api_server.py` reads it.

## Laravel Settings

In Laravel `.env`:

```env
RECOMMENDER_API_URL=https://your-public-api-url.com
RECOMMENDER_API_KEY=the-same-secret-key
```
