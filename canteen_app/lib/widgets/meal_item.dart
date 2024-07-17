import 'package:flutter/material.dart';

class MealItem extends StatefulWidget {
  final Map<String, dynamic> mealData;
  final String baseUrl;

  const MealItem({Key? key, required this.mealData, required this.baseUrl})
      : super(key: key);

  @override
  _MealItemState createState() => _MealItemState();
}

class _MealItemState extends State<MealItem> {
  bool _isFlipped = false;

  @override
  Widget build(BuildContext context) {
    String imageUrl = widget.mealData['todaymeal']['image'];
    String name = widget.mealData['todaymeal']['name'];

    return GestureDetector(
      onTap: () {
        setState(() {
          _isFlipped = !_isFlipped;
        });
      },
      child: AnimatedSwitcher(
        duration: const Duration(milliseconds: 500),
        child: _isFlipped
            ? BackCard(name: name)
            : FrontCard(
                baseUrl: widget.baseUrl,
                imageUrl: imageUrl,
                name: name,
              ),
      ),
    );
  }
}

class FrontCard extends StatelessWidget {
  final String baseUrl;
  final String imageUrl;
  final String name;

  const FrontCard({
    Key? key,
    required this.baseUrl,
    required this.imageUrl,
    required this.name,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(12),
      child: Card(
        elevation: 3,
        child: Stack(
          fit: StackFit.expand,
          children: [
            Image.network(
              imageUrl,
              fit: BoxFit.cover,
            ),
            Positioned(
              left: 0,
              right: 0,
              bottom: 0,
              child: Container(
                color: const Color.fromARGB(137, 109, 109, 109),
                padding: const EdgeInsets.symmetric(vertical: 8.0),
                child: Center(
                  child: Text(
                    name,
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class BackCard extends StatelessWidget {
  final String name;

  const BackCard({Key? key, required this.name}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(12),
      child: Card(
        elevation: 3,
        child: Center(
          child: Text(
            name,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
      ),
    );
  }
}
