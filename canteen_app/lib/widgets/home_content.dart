import 'package:flutter/material.dart';
import 'package:canteen_app/api_service.dart'; // Adjust import path as per your project
import 'package:canteen_app/widgets/meal_item.dart'; // Adjust import path as per your project

class HomeContent extends StatefulWidget {
  final String accessToken; // Receive accessToken from MainScreen
  final ApiService apiService;

  const HomeContent(
      {Key? key, required this.accessToken, required this.apiService})
      : super(key: key);

  @override
  _HomeContentState createState() => _HomeContentState();
}

class _HomeContentState extends State<HomeContent> {
  late ApiService _apiService;

  @override
  void initState() {
    super.initState();
    _apiService = widget.apiService;
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<Map<String, dynamic>>>(
      future: _apiService.fetchTodayMeals(widget.accessToken),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else if (snapshot.hasError) {
          return Center(child: Text('Error: ${snapshot.error}'));
        } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
          return const Center(child: Text('No meal images available'));
        } else {
          return GridView.builder(
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              mainAxisSpacing: 10.0,
              crossAxisSpacing: 10.0,
              childAspectRatio: 0.8,
            ),
            itemCount: snapshot.data!.length,
            itemBuilder: (context, index) {
              return MealItem(
                mealData: snapshot.data![index],
                baseUrl: _apiService.baseUrl,
              );
            },
          );
        }
      },
    );
  }
}
