# 📱 **STEP-BY-STEP MANUAL TESTING GUIDE**
## **Laravel Boilerplate + Flutter Mobile App**

### **🎯 TESTING OVERVIEW**
This guide provides exact steps, data inputs, and expected results for manual testing. Follow each step precisely to validate all functionality.

---

## **🔧 STEP 1: ENVIRONMENT SETUP**

### **💻 Prerequisites Check:**
```bash
✅ VERIFY SETUP:
1. Laravel server running: http://127.0.0.1:8000
2. Database: Check .env file for DB_DATABASE=laravelboilerplate
3. Flutter app: Ready to run on iOS Simulator/Android Emulator
4. Internet connection: Available for API calls
```

### **🗄️ Database Verification:**
```sql
-- Check your database (from .env file)
-- Default: DB_DATABASE=laravelboilerplate
-- Verify tables exist:
SHOW TABLES;
-- Expected: users, staff, messages, etc.

-- Verify test users exist (should show 22 users):
SELECT COUNT(*) FROM users;

-- Check specific test users:
SELECT name, email FROM users WHERE email IN (
  'admin@test.com',
  'john.doe@test.com',
  'ceo@test.com',
  'superadmin@test.com'
);
```

---

## **🧪 STEP 2: DETAILED TESTING SCENARIOS**

### **🚀 TEST MODULE 1: APP LAUNCH & SPLASH**

#### **📱 TEST CASE 1.1: First App Launch**
```
🎯 OBJECTIVE: Verify app launches correctly

📋 STEPS:
1. Open Terminal
2. Navigate to: cd flutter_app
3. Run: flutter run
4. Select device (iOS Simulator recommended for Mac M1)
5. Wait for app to launch

✅ EXPECTED RESULTS:
- Splash screen appears with app logo
- Loading animation shows for 2-3 seconds
- Smooth transition to login screen
- No crashes or errors in console

❌ FAILURE INDICATORS:
- App crashes on launch
- Blank white/black screen
- Error messages in console
- Takes more than 10 seconds to load
```

---

### **🔐 TEST MODULE 2: AUTHENTICATION SYSTEM**

#### **📱 TEST CASE 2.1: User Registration**
```
🎯 OBJECTIVE: Create new user account

📋 STEPS:
1. On login screen, tap "Don't have an account? Register"
2. Fill registration form:
   - Name: "John Doe"
   - Email: "john.doe@test.com"
   - Password: "password123"
   - Confirm Password: "password123"
3. Tap "Create Account" button
4. Wait for response

✅ EXPECTED RESULTS:
- Loading indicator appears on button
- Success message: "Account created successfully"
- Automatic redirect to home screen
- User logged in automatically
- Welcome message shows: "Welcome, John Doe!"

❌ FAILURE INDICATORS:
- Form validation errors
- Network error messages
- App crashes during registration
- No redirect after success
- User not logged in after registration

🔍 VERIFICATION:
- Check Laravel logs: tail -f storage/logs/laravel.log
- Check database: SELECT * FROM users WHERE email='john.doe@test.com';
- Verify user record created with correct data
```

#### **📱 TEST CASE 2.2: User Login (Existing User)**
```
🎯 OBJECTIVE: Login with existing credentials

📋 PREPARATION:
- Database seeded with test users (run: php artisan migrate:fresh --seed)
- Test users available: admin@test.com, john.doe@test.com, ceo@test.com, etc.

📋 STEPS:
1. On login screen, enter:
   - Email: "admin@test.com"
   - Password: "password123"
2. Tap "Login" button
3. Wait for authentication

✅ EXPECTED RESULTS:
- Loading indicator on login button
- Success authentication
- Redirect to home dashboard
- Home screen shows: "Welcome back, Admin!"
- Navigation drawer accessible
- User avatar displayed (initials or image)

❌ FAILURE INDICATORS:
- "Invalid credentials" error
- Network timeout errors
- App freezes during login
- No redirect after successful login
- Home screen shows wrong user data

🔍 VERIFICATION:
- Check secure storage for auth token
- Verify API call: POST /api/v1/auth/login
- Check response contains: access_token, user data
- Confirm token stored securely
```

#### **📱 TEST CASE 2.3: Forgot Password**
```
🎯 OBJECTIVE: Test password reset functionality

📋 STEPS:
1. On login screen, tap "Forgot Password?"
2. Enter email: "admin@test.com"
3. Tap "Send Reset Link"
4. Wait for response

✅ EXPECTED RESULTS:
- Loading indicator appears
- Success message: "Password reset link sent to your email"
- Return to login screen
- Email sent (check Laravel logs)

❌ FAILURE INDICATORS:
- "Email not found" error for valid email
- Network errors
- No success message
- App crashes

🔍 VERIFICATION:
- Check Laravel logs for email sending
- Verify API call: POST /api/v1/auth/forgot-password
- Check mail logs or mail catcher if configured
```

#### **📱 TEST CASE 2.4: Logout**
```
🎯 OBJECTIVE: Test user logout functionality

📋 STEPS:
1. From home screen, open navigation drawer
2. Scroll to bottom
3. Tap "Logout" button
4. Confirm logout in dialog

✅ EXPECTED RESULTS:
- Confirmation dialog appears
- After confirmation, redirect to login screen
- Auth token cleared from storage
- User session terminated
- Cannot access protected screens

❌ FAILURE INDICATORS:
- No confirmation dialog
- Still logged in after logout
- Can access protected screens
- Token not cleared

🔍 VERIFICATION:
- Check secure storage (should be empty)
- Try accessing protected API endpoints (should fail)
- Verify logout API call made
```

---

### **👥 TEST MODULE 3: USER MANAGEMENT**

#### **📱 TEST CASE 3.1: View Users List**
```
🎯 OBJECTIVE: Display and navigate users list

📋 PREPARATION:
- Ensure database has multiple users (use web app to create 5-10 users)
- Login to mobile app first

📋 STEPS:
1. From home screen, tap "Users" card
2. Wait for users list to load
3. Scroll through the list
4. Pull down to refresh

✅ EXPECTED RESULTS:
- Users list loads within 2 seconds
- Shows user cards with:
  - User avatar (initials or photo)
  - Full name
  - Email address
  - Role badge (if staff)
  - Online status indicator
- Pagination works (loads more on scroll)
- Pull-to-refresh updates the list
- Search bar visible at top

❌ FAILURE INDICATORS:
- Empty list when users exist in database
- Loading takes more than 5 seconds
- Missing user information
- Pagination not working
- Refresh doesn't update data

🔍 VERIFICATION:
- Check API call: GET /api/v1/users
- Verify response contains user array
- Check database: SELECT COUNT(*) FROM users;
- Compare mobile list count with database count
```

#### **📱 TEST CASE 3.2: Search Users**
```
🎯 OBJECTIVE: Test user search functionality

📋 STEPS:
1. On users list screen
2. Tap search bar at top
3. Type: "john"
4. Wait for search results
5. Clear search and try: "admin@test.com"

✅ EXPECTED RESULTS:
- Search results appear as you type (debounced)
- Shows users matching name or email
- Search is case-insensitive
- Clear button (X) appears in search bar
- Results update in real-time
- No results message if no matches

❌ FAILURE INDICATORS:
- Search doesn't work
- Results don't match search term
- Search is case-sensitive
- No debouncing (searches on every keystroke)
- App crashes during search

🔍 VERIFICATION:
- Check API calls with search parameters
- Verify search query in network logs
- Test with various search terms
```

#### **📱 TEST CASE 3.3: User Actions Menu**
```
🎯 OBJECTIVE: Test user action menu functionality

📋 STEPS:
1. On users list, find any user
2. Tap the three dots (⋮) menu on user card
3. Observe available options
4. Tap "View Profile"
5. Go back and try "Send Message"

✅ EXPECTED RESULTS:
- Menu appears with options:
  - View Profile
  - Send Message
  - Edit User (if admin)
  - Delete User (if admin)
- "View Profile" opens user detail screen
- "Send Message" navigates to chat
- Edit/Delete options work properly
- Proper permissions (admin-only actions)

❌ FAILURE INDICATORS:
- Menu doesn't appear
- Missing menu options
- Actions don't work
- Wrong permissions (non-admin sees admin actions)
- App crashes on menu tap

🔍 VERIFICATION:
- Check user permissions in response
- Verify role-based menu items
- Test with different user roles
```

---

### **💬 TEST MODULE 4: MESSAGING SYSTEM**

#### **📱 TEST CASE 4.1: Conversations List**
```
🎯 OBJECTIVE: View and manage conversations

📋 PREPARATION:
- Create test messages using web app or API
- Have at least 2-3 conversations

📋 STEPS:
1. From home screen, tap "Messages" card
2. Wait for conversations to load
3. Observe conversation list
4. Tap on a conversation

✅ EXPECTED RESULTS:
- Conversations list loads quickly
- Each conversation shows:
  - Other participant's avatar
  - Conversation title/name
  - Last message preview
  - Timestamp (formatted: "2h ago", "Yesterday")
  - Unread count badge (if any)
  - Pin indicator (if pinned)
  - Mute indicator (if muted)
- Tapping opens chat interface

❌ FAILURE INDICATORS:
- Empty list when conversations exist
- Missing conversation details
- Incorrect timestamps
- Unread counts wrong
- Tap doesn't open chat

🔍 VERIFICATION:
- Check API: GET /api/v1/conversations
- Verify conversation data in database
- Check message counts and timestamps
```

#### **📱 TEST CASE 4.2: Chat Interface**
```
🎯 OBJECTIVE: Test chat functionality

📋 STEPS:
1. Open any conversation from list
2. Observe chat interface
3. Scroll up to see older messages
4. Type a test message: "Hello from mobile app!"
5. Tap send button
6. Wait for message to appear

✅ EXPECTED RESULTS:
- Chat interface loads with:
  - User avatar and name in header
  - Message bubbles (different colors for sent/received)
  - Timestamps on messages
  - Message status indicators (sent/delivered/read)
  - Input field at bottom
  - Send button (changes from mic to send when typing)
- New message appears immediately
- Message shows "sending" then "sent" status
- Smooth scrolling

❌ FAILURE INDICATORS:
- Messages don't load
- Can't send messages
- Wrong message bubble colors
- Missing timestamps
- Input field not working
- App crashes in chat

🔍 VERIFICATION:
- Check API: GET /api/v1/conversations/{id}/messages
- Verify POST /api/v1/messages for sending
- Check message in database
- Verify real-time updates
```

#### **📱 TEST CASE 4.3: Message Attachments**
```
🎯 OBJECTIVE: Test file attachment functionality

📋 STEPS:
1. In chat interface, tap attachment button (📎)
2. Observe attachment options
3. Tap "Camera" option
4. Check permission request
5. Try "Gallery" option

✅ EXPECTED RESULTS:
- Bottom sheet appears with options:
  - Camera
  - Gallery
  - Document
  - Location (if implemented)
- Permission requests appear appropriately
- Options work (may show "coming soon" messages)
- Bottom sheet dismisses properly

❌ FAILURE INDICATORS:
- Attachment button doesn't work
- No bottom sheet appears
- Missing attachment options
- Permission errors
- App crashes on attachment tap

🔍 VERIFICATION:
- Check file picker integration
- Verify permission handling
- Test on different platforms (iOS/Android)
```

---

### **🔔 TEST MODULE 5: NOTIFICATIONS**

#### **📱 TEST CASE 5.1: Notification Permissions**
```
🎯 OBJECTIVE: Test notification permission handling

📋 STEPS:
1. Fresh app install or clear app data
2. Launch app and complete login
3. Navigate to Notifications page
4. Observe permission status
5. Tap "Enable Notifications" if shown

✅ EXPECTED RESULTS:
- Permission request dialog appears (iOS/Android)
- After granting: "Notifications enabled" status
- After denying: "Notifications disabled" with enable button
- Settings page reflects permission status
- FCM token generated and stored

❌ FAILURE INDICATORS:
- No permission request
- Wrong permission status shown
- Can't enable notifications
- App crashes on permission request

🔍 VERIFICATION:
- Check device notification settings
- Verify FCM token in app storage
- Check notification service initialization
```

#### **📱 TEST CASE 5.2: Local Notifications**
```
🎯 OBJECTIVE: Test local notification functionality

📋 STEPS:
1. Navigate to Notifications page
2. Tap "Test Notification" button
3. Wait for notification to appear
4. Check notification list
5. Tap on notification in list

✅ EXPECTED RESULTS:
- Local notification appears in system tray
- Notification added to in-app list
- Badge count updates
- Notification shows correct title and body
- Tapping notification opens relevant screen

❌ FAILURE INDICATORS:
- No notification appears
- Notification not added to list
- Wrong notification content
- Badge count not updating
- Tap doesn't work

🔍 VERIFICATION:
- Check system notification tray
- Verify notification service logs
- Check local notification storage
```

#### **📱 TEST CASE 5.3: Notification Management**
```
🎯 OBJECTIVE: Test notification list management

📋 STEPS:
1. Generate several test notifications
2. View notifications list
3. Tap menu on a notification
4. Try "Mark as read"
5. Try "Delete notification"
6. Use "Clear all" from app bar menu

✅ EXPECTED RESULTS:
- Notifications list shows all notifications
- Each notification shows:
  - Icon (message/general)
  - Title and body
  - Timestamp
  - Action menu
- "Mark as read" removes from unread count
- "Delete" removes notification
- "Clear all" empties the list
- Badge counts update correctly

❌ FAILURE INDICATORS:
- Actions don't work
- Badge counts wrong
- Notifications don't delete
- List doesn't update
- App crashes on actions

🔍 VERIFICATION:
- Check notification storage
- Verify badge count calculations
- Test notification cleanup
```

---

### **⚙️ TEST MODULE 6: SETTINGS & PREFERENCES**

#### **📱 TEST CASE 6.1: Theme Settings**
```
🎯 OBJECTIVE: Test theme switching functionality

📋 STEPS:
1. Navigate to Settings page
2. Find "Appearance" section
3. Tap "Theme" option
4. Try each theme option:
   - System (default)
   - Light
   - Dark
5. Observe app-wide theme changes

✅ EXPECTED RESULTS:
- Theme selection dialog appears
- Three options available with radio buttons
- Selecting theme changes app immediately
- All screens adapt to new theme
- Setting persists after app restart
- System theme follows device setting

❌ FAILURE INDICATORS:
- Theme doesn't change
- Only some screens change theme
- Setting doesn't persist
- System theme doesn't work
- App crashes on theme change

🔍 VERIFICATION:
- Check theme persistence in storage
- Verify all screens use theme
- Test system theme with device changes
```

#### **📱 TEST CASE 6.2: Language Settings**
```
🎯 OBJECTIVE: Test multi-language support

📋 STEPS:
1. In Settings, tap "Language"
2. Observe available languages
3. Select "Spanish (Español)"
4. Observe app language change
5. Try "Arabic (العربية)" for RTL testing

✅ EXPECTED RESULTS:
- Language selection dialog with 11 languages
- App text changes to selected language
- RTL languages (Arabic) flip layout
- Setting persists after restart
- All screens show translated text

❌ FAILURE INDICATORS:
- Limited language options
- Text doesn't translate
- RTL layout issues
- Setting doesn't persist
- Missing translations

🔍 VERIFICATION:
- Check translation files
- Verify RTL layout support
- Test with different languages
```

#### **📱 TEST CASE 6.3: Font Size Settings**
```
🎯 OBJECTIVE: Test accessibility font sizing

📋 STEPS:
1. In Settings, tap "Font Size"
2. Try each size option:
   - Small
   - Medium (default)
   - Large
   - Extra Large
3. Navigate through app to see changes

✅ EXPECTED RESULTS:
- Font size dialog with 4 options
- Text size changes throughout app
- Layout adapts to larger text
- Setting persists
- Accessibility compliance

❌ FAILURE INDICATORS:
- Font size doesn't change
- Layout breaks with large text
- Only some text changes
- Setting doesn't persist

🔍 VERIFICATION:
- Check font scaling implementation
- Verify accessibility compliance
- Test layout adaptation
```

#### **📱 TEST CASE 6.4: Notification Preferences**
```
🎯 OBJECTIVE: Test notification settings

📋 STEPS:
1. In Settings, find "Notifications" section
2. Toggle "Enable Notifications" off
3. Observe child settings become disabled
4. Toggle back on
5. Change "Notification Frequency" to "Hourly"
6. Test other notification settings

✅ EXPECTED RESULTS:
- Master toggle controls all notifications
- Child settings disabled when master is off
- Frequency options: Immediately, 15min, Hourly, Daily, Never
- Settings save automatically
- Smart dependency management

❌ FAILURE INDICATORS:
- Child settings don't disable
- Frequency options missing
- Settings don't save
- No dependency management

🔍 VERIFICATION:
- Check settings storage
- Verify notification behavior changes
- Test setting dependencies
```

---

### **🔄 TEST MODULE 7: OFFLINE/SYNC FUNCTIONALITY**

#### **📱 TEST CASE 7.1: Offline Mode**
```
🎯 OBJECTIVE: Test app functionality without internet

📋 STEPS:
1. Ensure app is fully synced and online
2. Turn off WiFi and mobile data
3. Navigate through app:
   - View users list
   - Open conversations
   - Try to send a message
   - Check settings
4. Observe offline indicators

✅ EXPECTED RESULTS:
- App continues to work smoothly
- Data loads from local database
- Sync indicator shows "Offline"
- Users list shows cached users
- Messages show from local storage
- New messages queued for sync
- Settings work normally
- No crashes or errors

❌ FAILURE INDICATORS:
- App becomes unusable offline
- Data doesn't load
- No offline indicators
- App crashes without internet
- Settings don't work

🔍 VERIFICATION:
- Check local database has data
- Verify offline queue for pending operations
- Check sync status indicators
```

#### **📱 TEST CASE 7.2: Online Sync**
```
🎯 OBJECTIVE: Test synchronization when back online

📋 STEPS:
1. While offline, make changes:
   - Edit user profile
   - Send a message
   - Change settings
2. Turn internet back on
3. Observe sync process
4. Check if changes appear on web app

✅ EXPECTED RESULTS:
- Automatic sync starts when online
- Sync progress indicator appears
- Local changes uploaded to server
- Server changes downloaded
- Conflicts resolved automatically
- Sync status shows "Synced"
- Changes visible on web app

❌ FAILURE INDICATORS:
- No automatic sync
- Changes not uploaded
- Server changes not downloaded
- Sync conflicts not resolved
- Data inconsistency

🔍 VERIFICATION:
- Check sync queue processing
- Verify API calls for sync operations
- Compare data between mobile and web
- Check conflict resolution logs
```

#### **📱 TEST CASE 7.3: Background Sync**
```
🎯 OBJECTIVE: Test background synchronization

📋 STEPS:
1. Make changes on web app (add user, send message)
2. Put mobile app in background
3. Wait 2-3 minutes
4. Bring app to foreground
5. Check for updates

✅ EXPECTED RESULTS:
- Background sync occurs automatically
- New data appears when app returns to foreground
- Notifications received for new messages
- Sync happens without user intervention
- Battery usage remains reasonable

❌ FAILURE INDICATORS:
- No background sync
- Data not updated
- No notifications received
- Excessive battery usage
- Sync only works when app active

🔍 VERIFICATION:
- Check WorkManager logs
- Verify background sync configuration
- Monitor battery usage
- Check notification delivery
```

---

### **📊 TEST MODULE 8: PERFORMANCE & RELIABILITY**

#### **📱 TEST CASE 8.1: App Performance**
```
🎯 OBJECTIVE: Verify app performance metrics

📋 STEPS:
1. Measure app launch time (cold start)
2. Navigate between screens rapidly
3. Load large users list (100+ users)
4. Send multiple messages quickly
5. Monitor memory usage

✅ EXPECTED RESULTS:
- Cold start: < 3 seconds
- Warm start: < 1 second
- Smooth navigation (60 FPS)
- Large lists load efficiently
- Memory usage < 100MB
- No memory leaks
- Responsive UI interactions

❌ FAILURE INDICATORS:
- Slow app launch (> 5 seconds)
- Laggy navigation
- High memory usage (> 200MB)
- Memory leaks over time
- Unresponsive UI

🔍 VERIFICATION:
- Use Flutter DevTools for performance monitoring
- Check memory usage in device settings
- Monitor CPU usage
- Test on different devices
```

#### **📱 TEST CASE 8.2: Error Handling**
```
🎯 OBJECTIVE: Test app resilience to errors

📋 STEPS:
1. Stop Laravel server while using app
2. Try various actions (login, load users, send message)
3. Enter invalid data in forms
4. Try actions without permissions
5. Restart server and observe recovery

✅ EXPECTED RESULTS:
- Graceful error messages (no crashes)
- Offline mode activates when server down
- Form validation prevents invalid data
- Permission errors handled properly
- Automatic recovery when server returns
- User-friendly error messages

❌ FAILURE INDICATORS:
- App crashes on errors
- No error messages shown
- Invalid data accepted
- No offline fallback
- Poor error recovery

🔍 VERIFICATION:
- Check error logs
- Verify error handling code paths
- Test various error scenarios
- Monitor app stability
```

---

## **✅ TESTING COMPLETION CHECKLIST**

### **📋 Module Completion:**
- [ ] **App Launch & Splash** - All tests passed
- [ ] **Authentication System** - Login, register, logout working
- [ ] **User Management** - List, search, actions functional
- [ ] **Messaging System** - Chat interface and attachments working
- [ ] **Notifications** - Permissions, local notifications, management
- [ ] **Settings & Preferences** - Theme, language, font size, notifications
- [ ] **Offline/Sync** - Offline mode, sync, background sync
- [ ] **Performance & Reliability** - Performance metrics, error handling

### **🎯 Success Criteria:**
- [ ] All critical functionality works
- [ ] No app crashes during testing
- [ ] Data syncs between mobile and web
- [ ] Offline mode functions properly
- [ ] Performance meets targets
- [ ] Error handling is robust
- [ ] User experience is smooth

---

## **📞 TROUBLESHOOTING QUICK FIXES**

### **🔧 Common Issues:**

#### **🌐 API Connection Issues:**
```bash
# Check Laravel server is running
curl http://127.0.0.1:8000/api/v1/health

# For Android Emulator, use:
# http://10.0.2.2:8000/api/v1

# For physical device, use your local IP:
# http://192.168.1.XXX:8000/api/v1
```

#### **📱 Flutter Build Issues:**
```bash
# Clean and rebuild
flutter clean
flutter pub get
flutter packages pub run build_runner build --delete-conflicting-outputs
flutter run
```

#### **🗄️ Database Issues:**
```bash
# Reset database
php artisan migrate:fresh --seed

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## **🎉 TESTING COMPLETE!**

**When all tests pass, your Laravel Boilerplate + Flutter Mobile App is ready for production deployment!** 🚀

**Use this guide for future projects by adapting the test data and expected results to your specific application requirements.**
