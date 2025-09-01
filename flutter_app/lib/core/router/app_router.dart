import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../presentation/pages/splash/splash_page.dart';
import '../../presentation/pages/auth/login_page.dart';
import '../../presentation/pages/auth/register_page.dart';
import '../../presentation/pages/auth/forgot_password_page.dart';
import '../../presentation/pages/home/home_page.dart';
import '../../presentation/pages/profile/profile_page.dart';
import '../../presentation/pages/users/users_page.dart';
import '../../presentation/pages/users/user_detail_page.dart';
import '../../presentation/pages/messages/conversations_page.dart';
import '../../presentation/pages/messages/chat_page.dart';
import '../../presentation/pages/notifications/notifications_page.dart';
import '../../presentation/pages/settings/settings_page.dart';
import '../../presentation/providers/auth_provider.dart';

// Route names
class AppRoutes {
  static const String splash = '/';
  static const String login = '/login';
  static const String register = '/register';
  static const String forgotPassword = '/forgot-password';
  static const String home = '/home';
  static const String profile = '/profile';
  static const String users = '/users';
  static const String userDetail = '/users/:id';
  static const String messages = '/messages';
  static const String chat = '/messages/chat/:userId';
  static const String notifications = '/notifications';
  static const String settings = '/settings';
}

// Router provider
final routerProvider = Provider<GoRouter>((ref) {
  final authState = ref.watch(authProvider);
  
  return GoRouter(
    initialLocation: AppRoutes.splash,
    debugLogDiagnostics: true,
    redirect: (context, state) {
      final isAuthenticated = authState.isAuthenticated;
      final isLoading = authState.isLoading;
      
      // Show splash while loading
      if (isLoading) {
        return AppRoutes.splash;
      }
      
      // Define public routes (accessible without authentication)
      final publicRoutes = [
        AppRoutes.splash,
        AppRoutes.login,
        AppRoutes.register,
        AppRoutes.forgotPassword,
      ];
      
      final isPublicRoute = publicRoutes.contains(state.matchedLocation);
      
      // Redirect to login if not authenticated and trying to access private route
      if (!isAuthenticated && !isPublicRoute) {
        return AppRoutes.login;
      }
      
      // Redirect to home if authenticated and trying to access auth routes
      if (isAuthenticated && (state.matchedLocation == AppRoutes.login || 
                             state.matchedLocation == AppRoutes.register)) {
        return AppRoutes.home;
      }
      
      // No redirect needed
      return null;
    },
    routes: [
      // Splash route
      GoRoute(
        path: AppRoutes.splash,
        name: 'splash',
        builder: (context, state) => const SplashPage(),
      ),
      
      // Authentication routes
      GoRoute(
        path: AppRoutes.login,
        name: 'login',
        builder: (context, state) => const LoginPage(),
      ),
      GoRoute(
        path: AppRoutes.register,
        name: 'register',
        builder: (context, state) => const RegisterPage(),
      ),
      GoRoute(
        path: AppRoutes.forgotPassword,
        name: 'forgot-password',
        builder: (context, state) => const ForgotPasswordPage(),
      ),
      
      // Main app routes
      GoRoute(
        path: AppRoutes.home,
        name: 'home',
        builder: (context, state) => const HomePage(),
      ),
      
      // Profile routes
      GoRoute(
        path: AppRoutes.profile,
        name: 'profile',
        builder: (context, state) => const ProfilePage(),
      ),
      
      // Users routes
      GoRoute(
        path: AppRoutes.users,
        name: 'users',
        builder: (context, state) => const UsersPage(),
        routes: [
          GoRoute(
            path: ':id',
            name: 'user-detail',
            builder: (context, state) {
              final userId = state.pathParameters['id']!;
              return UserDetailPage(userId: userId);
            },
          ),
        ],
      ),
      
      // Messages routes
      GoRoute(
        path: AppRoutes.messages,
        name: 'messages',
        builder: (context, state) => const ConversationsPage(),
        routes: [
          GoRoute(
            path: 'chat/:userId',
            name: 'chat',
            builder: (context, state) {
              final userId = state.pathParameters['userId']!;
              final userName = state.uri.queryParameters['userName'];
              return ChatPage(
                userId: userId,
                userName: userName,
              );
            },
          ),
        ],
      ),
      
      // Notifications route
      GoRoute(
        path: AppRoutes.notifications,
        name: 'notifications',
        builder: (context, state) => const NotificationsPage(),
      ),
      
      // Settings route
      GoRoute(
        path: AppRoutes.settings,
        name: 'settings',
        builder: (context, state) => const SettingsPage(),
      ),
    ],
    
    // Error page
    errorBuilder: (context, state) => Scaffold(
      appBar: AppBar(title: const Text('Error')),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              Icons.error_outline,
              size: 64,
              color: Colors.red,
            ),
            const SizedBox(height: 16),
            Text(
              'Page not found',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 8),
            Text(
              'The page "${state.matchedLocation}" could not be found.',
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.go(AppRoutes.home),
              child: const Text('Go Home'),
            ),
          ],
        ),
      ),
    ),
  );
});

// Navigation helper extension
extension GoRouterExtension on GoRouter {
  void pushAndClearStack(String location) {
    while (canPop()) {
      pop();
    }
    pushReplacement(location);
  }
}

// Navigation helper methods
class AppNavigation {
  static void toLogin(BuildContext context) {
    context.go(AppRoutes.login);
  }
  
  static void toHome(BuildContext context) {
    context.go(AppRoutes.home);
  }
  
  static void toProfile(BuildContext context) {
    context.push(AppRoutes.profile);
  }
  
  static void toUsers(BuildContext context) {
    context.push(AppRoutes.users);
  }
  
  static void toUserDetail(BuildContext context, String userId) {
    context.push('/users/$userId');
  }
  
  static void toMessages(BuildContext context) {
    context.push(AppRoutes.messages);
  }
  
  static void toChat(BuildContext context, String userId, {String? userName}) {
    final uri = Uri(
      path: '/messages/chat/$userId',
      queryParameters: userName != null ? {'userName': userName} : null,
    );
    context.push(uri.toString());
  }
  
  static void toNotifications(BuildContext context) {
    context.push(AppRoutes.notifications);
  }
  
  static void toSettings(BuildContext context) {
    context.push(AppRoutes.settings);
  }
  
  static void back(BuildContext context) {
    if (context.canPop()) {
      context.pop();
    } else {
      context.go(AppRoutes.home);
    }
  }
}
