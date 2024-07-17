import 'package:flutter/material.dart';
import 'package:canteen_app/widgets/circle_back.dart';

class ProfileContent extends StatelessWidget {
  const ProfileContent({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: const [
          // Center(child: CircleBackButton()),
          SizedBox(height: 80),
          Text('Profile Content', style: TextStyle(fontSize: 24)),
        ],
      ),
    );
  }
}
