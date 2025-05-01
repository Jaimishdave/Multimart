# Use the official PHP base image
FROM php:8.2-cli

# Set working directory inside the container
WORKDIR /app

# Copy all files from your project into the container
COPY . /app

# Expose port 10000 (Render expects this port)
EXPOSE 10000

# Start the PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
