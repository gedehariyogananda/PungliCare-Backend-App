1. Install dependencies
    ```bash
    composer install
    ```
    Js depedency
    ```bash
    npm install && npm run dev
    ```

2. ENV e
    ```bash
    APP_NAME=PungliCare
    APP_ENV=local
    APP_KEY=base64:Eszi20usN6dQJ45qTKPH8PpVy50Wxszz72EnKR+jd08=
    APP_DEBUG=true
    APP_URL=http://localhost
    
    LOG_CHANNEL=stack
    LOG_LEVEL=debug
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=punglicare
    DB_USERNAME=root
    DB_PASSWORD=
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=database
    SESSION_LIFETIME=120
    
    MEMCACHED_HOST=127.0.0.1
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=465
    MAIL_USERNAME="pungli.care@gmail.com"
    MAIL_PASSWORD="gyyaxqeucqadcmfu"
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="pungli.care@gmail.com"
    MAIL_FROM_NAME="${APP_NAME}"
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1
    
    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    
    TWILIO_SID="sid twilio"
    TWILIO_TOKEN="token twilio"
    TWILIO_PHONE="service twillo number"
    JWT_SECRET="ur jwt secret"

    ```

3. Storage::Link setup
    ```bash
    php artisan storage:link
    ```

4. Migrate database sedder
    ```bash
    php artisan migrate --seed
    ```

5. Serve the application
    ```bash
    php artisan serve
    ```

7. Login credentials
    ```bash
    role = masyarakat-umum
    **Email:** ari@gmail.com
    **Password:** password
    
    role = kapolsek
    **Email:** kapolsek@gmail.com
    **Password:** password

    ```
