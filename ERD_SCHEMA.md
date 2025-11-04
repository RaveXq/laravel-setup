erDiagram
    users ||--o{ projects : owns
    users }o--o{ projects : participates
    users ||--o{ tasks : authors
    users ||--o{ tasks : assigned_to
    users ||--o{ comments : writes
    users ||--o{ personal_access_tokens : has_tokens

    projects ||--o{ tasks : contains
    projects }o--o{ users : has_members

    tasks ||--o{ comments : has

    users {
        bigint id PK
        string name
        string email UK
        string password
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    projects {
        bigint id PK
        bigint owner_id FK
        string name
        timestamp created_at
        timestamp updated_at
    }

    project_user {
        bigint project_id FK,PK
        bigint user_id FK,PK
        string role
    }

    tasks {
        bigint id PK
        bigint project_id FK
        bigint author_id FK
        bigint assignee_id FK
        string title
        text description
        string status
        string priority
        date due_date
        timestamp created_at
        timestamp updated_at
    }

    comments {
        bigint id PK
        bigint task_id FK
        bigint author_id FK
        text body
        timestamp created_at
        timestamp updated_at
    }

    reports {
        bigint id PK
        date period_start
        date period_end
        json payload
        string path
        timestamp created_at
        timestamp updated_at
    }

    personal_access_tokens {
        bigint id PK
        string tokenable_type
        bigint tokenable_id
        text name
        string token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }
