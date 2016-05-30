 int valor=1;
 
void setup() {

  pinMode(13,OUTPUT);
  Serial.begin(9600);
}

void loop() {
  
 // Serial.flush(); //vacia buffer de entrada
  
  if( Serial.available()>0) {
    valor = Serial.read();
    
    if(valor=='1')
      digitalWrite(13,HIGH);
    if(valor=='0')
      digitalWrite(13, LOW);
       
  }
}
