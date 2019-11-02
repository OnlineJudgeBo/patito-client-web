#include <bits/stdc++.h>
using namespace std;

int A[9]={2, 3, 8, 10, -2, 4, 5, 3, -1};
int ST[32];

void crearST(int nodo, int a, int b){ // Complejidad O(n)
	if(a==b){
		ST[nodo] = A[a]; return;
	}
	crearST(nodo*2, a, (a+b)/2);
	crearST((nodo*2)+1,((a+b)/2)+1, b);
	ST[nodo] = min(ST[nodo*2], ST[(nodo*2)+1]);
}

int queryST(int nodo, int a, int b, int i, int j){
	int m = (a+b)/2;
	//cout<<nodo<<endl;
	if(a==b) return ST[nodo];
	if(j<=m){ // Solo mi hijo izquierdo
		return queryST(nodo*2, a, m, i, j);
	}
	if(m<i){ // Solo mi hijo derecho
		return queryST((nodo*2)+1, m+1, b, i, j); 
	}
	return min(
	queryST(nodo*2, a, m, i, j) ,
		queryST((nodo*2)+1, m+1, b, i, j)
		);
}

void updateST(int nodo, int a, int b, int i, int v){ // Complejidad O(log n)
	if(i<a or b<i) return ; // solo esta linea aumente...
	if(a==b){
		ST[nodo] = A[a] = v; return; // esta mas ...
	}
	updateST(nodo*2, a, (a+b)/2, i, v);
	updateST((nodo*2)+1,((a+b)/2)+1, b, i, v);
	ST[nodo] = min(ST[nodo*2], ST[(nodo*2)+1]);
}

int main(){
	cout<<"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
	crearST(1, 0, 8);
	for(int i=0; i<32; i++){
		cout<<ST[i]<<" ";
	}cout<<endl;
	cout<<" minimo de 0 a 8 es:"<< queryST(1, 0, 8, 0, 8)<<endl;
	cout<<" minimo de 0 a 4 es:"<< queryST(1, 0, 8, 0, 4)<<endl;
	cout<<" minimo de 0 a 7 es:"<< queryST(1, 0, 8, 0, 7)<<endl;
	cout<<" minimo de 5 a 7 es:"<< queryST(1, 0, 8, 5, 7)<<endl;
	cout<<" minimo de 5 a 8 es:"<< queryST(1, 0, 8, 5, 8)<<endl;
	for(int i=0; i<18; i++){
		cout<<ST[i]<<" ";
	}cout<<endl;
	updateST(1, 0, 8, 4, 11);
	for(int i=0; i<18; i++){
		cout<<ST[i]<<" ";
	}cout<<endl;
	return 0;
}
