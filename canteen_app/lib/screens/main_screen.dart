import 'package:flutter/material.dart';
import 'package:canteen_app/api_service.dart'; // Adjust import path as per your project
import 'package:canteen_app/screens/login_screen.dart';
import 'package:canteen_app/component/bottom_navbar.dart'; // Import the BottomNavBar component
import 'package:canteen_app/widgets/home_content.dart'; // Import the HomeContent widget
import 'package:canteen_app/widgets/profile_widget.dart'; // Import the ProfileContent widget

class MainScreen extends StatefulWidget {
  final String accessToken;

  const MainScreen({Key? key, required this.accessToken}) : super(key: key);

  @override
  _MainScreenState createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  final ApiService _apiService = ApiService();
  Map<String, dynamic>? _userData;
  bool _isLoading = false;
  int _selectedIndex = 0;

  @override
  void initState() {
    super.initState();
    _fetchUserData();
  }

  Future<void> _fetchUserData() async {
    setState(() {
      _isLoading = true;
    });

    try {
      Map<String, dynamic> userData =
          await _apiService.fetchSelf(widget.accessToken);
      setState(() {
        _userData = userData;
        _isLoading = false;
      });
    } catch (e) {
      print('Error fetching user data: $e');
      setState(() {
        _isLoading = false;
      });
    }
  }

  void _logout() {
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => const LoginScreen()),
      (route) => false, // Remove all other routes from the stack
    );
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: const Color.fromARGB(255, 216, 58, 58),
        automaticallyImplyLeading: false,
        actions: _userData != null
            ? [
                Padding(
                  padding: const EdgeInsets.only(right: 16.0),
                  child: PopupMenuButton<String>(
                    onSelected: (String result) {
                      if (result == 'logout') {
                        _logout();
                      }
                    },
                    child: Row(
                      children: [
                        if (_userData!['profile'] != null)
                          CircleAvatar(
                            backgroundImage: NetworkImage(
                              '${_apiService.baseUrl}/profile/${_userData!['profile']}',
                            ),
                            radius: 16,
                          )
                        else
                          const CircleAvatar(
                            backgroundImage: AssetImage(
                              'assets/default_profile.png',
                            ),
                            radius: 16,
                          ),
                        const SizedBox(width: 8),
                        const Icon(Icons.arrow_drop_down),
                      ],
                    ),
                    itemBuilder: (BuildContext context) =>
                        <PopupMenuEntry<String>>[
                      const PopupMenuItem<String>(
                        value: 'logout',
                        child: Text('Logout'),
                      ),
                    ],
                  ),
                ),
              ]
            : [],
      ),
      body: Center(
        child: _isLoading
            ? const CircularProgressIndicator()
            : _userData != null
                ? _selectedIndex == 0
                    ? HomeContent(
                        accessToken: widget.accessToken,
                        apiService: _apiService, // Pass apiService instance
                      )
                    : const ProfileContent()
                : const Text(
                    'User data not available',
                    style: TextStyle(fontSize: 18),
                  ),
      ),
      bottomNavigationBar: BottomNavBar(
        currentIndex: _selectedIndex,
        onTap: _onItemTapped,
      ),
    );
  }
}
