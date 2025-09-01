/// Application-wide constants
class AppConstants {
  // App Information
  static const String appName = 'Laravel Boilerplate Mobile';
  static const String appVersion = '1.0.0';
  static const String appBuildNumber = '1';
  
  // API Configuration
  // For localhost testing:
  // iOS Simulator: 'http://127.0.0.1:8000'
  // Android Emulator: 'http://10.0.2.2:8000'
  // Physical Device: 'http://YOUR_LOCAL_IP:8000'
  static const String baseUrl = 'http://127.0.0.1:8000';
  static const String apiVersion = 'v1';
  static const String apiBaseUrl = '$baseUrl/api/$apiVersion';
  
  // WebSocket Configuration
  static const String websocketUrl = 'ws://localhost:8080';
  
  // Storage Keys
  static const String accessTokenKey = 'access_token';
  static const String refreshTokenKey = 'refresh_token';
  static const String userDataKey = 'user_data';
  static const String themeKey = 'theme_mode';
  static const String languageKey = 'language';
  static const String biometricEnabledKey = 'biometric_enabled';
  
  // Cache Keys
  static const String userCacheKey = 'user_cache';
  static const String conversationsCacheKey = 'conversations_cache';
  static const String messagesCacheKey = 'messages_cache';
  static const String notificationsCacheKey = 'notifications_cache';
  
  // Pagination
  static const int defaultPageSize = 20;
  static const int maxPageSize = 100;
  
  // File Upload
  static const int maxFileSize = 20 * 1024 * 1024; // 20MB
  static const List<String> allowedImageTypes = [
    'jpg', 'jpeg', 'png', 'gif', 'webp'
  ];
  static const List<String> allowedDocumentTypes = [
    'pdf', 'doc', 'docx', 'txt', 'rtf'
  ];
  static const List<String> allowedVideoTypes = [
    'mp4', 'avi', 'mov', 'wmv', 'flv'
  ];
  static const List<String> allowedAudioTypes = [
    'mp3', 'wav', 'aac', 'ogg', 'm4a'
  ];
  
  // Timeouts
  static const Duration connectTimeout = Duration(seconds: 30);
  static const Duration receiveTimeout = Duration(seconds: 30);
  static const Duration sendTimeout = Duration(seconds: 30);
  
  // Cache Duration
  static const Duration shortCacheDuration = Duration(minutes: 5);
  static const Duration mediumCacheDuration = Duration(hours: 1);
  static const Duration longCacheDuration = Duration(days: 1);
  
  // Animation Durations
  static const Duration shortAnimationDuration = Duration(milliseconds: 200);
  static const Duration mediumAnimationDuration = Duration(milliseconds: 300);
  static const Duration longAnimationDuration = Duration(milliseconds: 500);
  
  // UI Constants
  static const double defaultPadding = 16.0;
  static const double smallPadding = 8.0;
  static const double largePadding = 24.0;
  static const double defaultBorderRadius = 8.0;
  static const double smallBorderRadius = 4.0;
  static const double largeBorderRadius = 16.0;
  
  // Message Constants
  static const int maxMessageLength = 5000;
  static const int typingIndicatorDuration = 5; // seconds
  static const int messageRetryAttempts = 3;
  
  // Notification Constants
  static const String notificationChannelId = 'laravel_boilerplate_notifications';
  static const String notificationChannelName = 'Laravel Boilerplate Notifications';
  static const String notificationChannelDescription = 'Notifications from Laravel Boilerplate app';
  
  // Error Messages
  static const String genericErrorMessage = 'Something went wrong. Please try again.';
  static const String networkErrorMessage = 'Please check your internet connection and try again.';
  static const String timeoutErrorMessage = 'Request timed out. Please try again.';
  static const String unauthorizedErrorMessage = 'You are not authorized to perform this action.';
  static const String notFoundErrorMessage = 'The requested resource was not found.';
  
  // Success Messages
  static const String loginSuccessMessage = 'Login successful!';
  static const String logoutSuccessMessage = 'Logout successful!';
  static const String registrationSuccessMessage = 'Registration successful!';
  static const String passwordResetSuccessMessage = 'Password reset email sent!';
  static const String profileUpdateSuccessMessage = 'Profile updated successfully!';
  static const String messageSentSuccessMessage = 'Message sent successfully!';
  
  // Validation
  static const int minPasswordLength = 8;
  static const int maxPasswordLength = 128;
  static const int minUsernameLength = 3;
  static const int maxUsernameLength = 50;
  
  // Regular Expressions
  static const String emailRegex = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
  static const String phoneRegex = r'^\+?[1-9]\d{1,14}$';
  static const String passwordRegex = r'^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]';
  
  // Feature Flags
  static const bool enableBiometricAuth = true;
  static const bool enablePushNotifications = true;
  static const bool enableOfflineMode = true;
  static const bool enableAnalytics = true;
  static const bool enableCrashReporting = true;
  
  // Environment
  static const String environment = String.fromEnvironment('ENVIRONMENT', defaultValue: 'development');
  static const bool isProduction = environment == 'production';
  static const bool isDevelopment = environment == 'development';
  static const bool isStaging = environment == 'staging';
}
