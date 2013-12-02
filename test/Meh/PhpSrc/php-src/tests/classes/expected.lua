--class Circle {
--  function draw() {
--    echo "Circle\n";
--  }
--  }
--
--  class Square {
--    function draw() {
--      print "Square\n";
--    }
--    }
--
--    function ShapeFactoryMethod($shape) {
--      switch ($shape) {
--      case "Circle":
--      return new Circle();
--    case "Square":
--    return new Square();
--    }
--    }
--
--    ShapeFactoryMethod("Circle")->draw();
--    ShapeFactoryMethod("Square")->draw();

php.classNs({}).Circle = php.defineClass {
  name = Circle,
  methods = {
    draw = function(self)
      php.echo("Circle\n")
    end
  }
}

php.classNs({}).Square = php.defineClass {
  name = Square,
  methods = {
    draw = function(self)
      php.echo("Square\n")
    end
  }
}