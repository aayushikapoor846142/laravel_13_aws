# Customer Support Knowledge Base with RAG

A real-world AI-powered customer support system using Retrieval-Augmented Generation (RAG).

## Features

✅ **Smart Question Answering** - Customers ask questions in natural language and get instant AI-generated answers
✅ **Semantic Search** - Uses embeddings to find the most relevant articles, not just keyword matching
✅ **Source Attribution** - Shows which knowledge base articles were used to generate the answer
✅ **Feedback System** - Tracks if answers were helpful to improve the system
✅ **Analytics Dashboard** - See which articles are most viewed and helpful
✅ **Category Browsing** - Traditional browsing by category for users who prefer it
✅ **Admin Panel** - Add and manage knowledge base articles

## How It Works

1. **Document Ingestion**: Articles are stored with AI-generated embeddings (vector representations)
2. **Query Processing**: When a user asks a question, it's converted to an embedding
3. **Semantic Search**: Find the 3 most similar articles using cosine similarity
4. **Answer Generation**: GPT uses the relevant articles as context to generate a natural answer
5. **Feedback Loop**: User feedback helps identify which articles are most helpful

## Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Configure OpenAI API
Add your OpenAI API key to `.env`:
```
OPENAI_API_KEY=sk-your-api-key-here
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Seed Knowledge Base
```bash
php artisan db:seed --class=KnowledgeBaseSeeder
```

### 5. Build Assets
```bash
npm run build
```

### 6. Start Server
```bash
php artisan serve
```

Visit: `http://localhost:8000/support`

## Usage

### For Customers
1. Go to `/support`
2. Type your question in natural language
3. Get an instant AI-generated answer with sources
4. Provide feedback on whether it was helpful

### For Admins
Add new articles via API:
```bash
POST /support/articles
{
    "title": "How to do something",
    "content": "Step by step instructions...",
    "category": "Category Name"
}
```

View analytics:
```
GET /support/analytics
```

## Real-World Benefits

- **24/7 Support**: Instant answers without waiting for human agents
- **Cost Reduction**: Reduces support ticket volume by 40-60%
- **Consistency**: Same quality answers every time
- **Scalability**: Handles unlimited concurrent users
- **Continuous Improvement**: Learns from feedback which articles are most helpful

## API Endpoints

- `GET /support` - Main support page
- `POST /support/ask` - Ask a question (returns AI answer)
- `POST /support/feedback/{queryId}` - Submit feedback
- `GET /support/category/{category}` - Browse by category
- `GET /support/article/{id}` - View single article
- `POST /support/articles` - Add article (admin)
- `GET /support/analytics` - View analytics (admin)

## Database Schema

### kb_articles
- Stores knowledge base articles with embeddings
- Tracks views and helpful counts

### support_queries
- Logs all customer questions and answers
- Tracks feedback for improvement

## Cost Estimation

Using OpenAI API:
- Embedding: ~$0.0001 per article
- Answer generation: ~$0.002 per question
- For 1000 questions/day: ~$2/day or $60/month

Much cheaper than human support agents!

## Future Enhancements

- Multi-language support
- Voice input/output
- Integration with live chat (escalate if RAG can't answer)
- Auto-generate articles from support tickets
- A/B testing different prompts
- Fine-tuned models for better accuracy

## License

MIT
