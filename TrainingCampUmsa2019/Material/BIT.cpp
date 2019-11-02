#include <iostream>
using namespace std;


int A[16]={0,2,3,-1,5,-3,2,7,-8,-2,4,3,1,12,-3,2};
int T[16]={0};

void update(int i, int v){
	int idx = i;
	while(idx <16){
		T[idx] = T[idx]+v;
		idx = idx + (idx & (-idx));
	}
}


void crearFT(){
	for(int i =1; i<16; i++){
		update(i, A[i]);
	}
}



int sum(int i){
	int idx = i;
	int sum = 0;
	while(idx>0){
		sum = sum +T[idx];
		idx = idx - (idx & (-idx));
	}
	return sum;
}


int main() {
	crearFT();
	cout<<sum(15)<<endl;
	// Cambiar la posicion 1 a 8
	cout<<sum(1)<<endl;
	update(1, 6);
	cout<<sum(1)<<endl;
	cout<<sum(15)<<endl;
	// your code goes here
	return 0;
}