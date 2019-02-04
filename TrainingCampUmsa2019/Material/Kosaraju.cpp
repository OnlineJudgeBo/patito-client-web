#include <bits/stdc++.h>
using namespace std;

vector<int> G[8];
vector<int> Ginv[8];
int Vis[8], O[8], io=0;
int Etiqueta[8];

void dfs1(int u){
	Vis[u] = 1;
	//cout<<u<<endl;
	for(int i=0; i<Ginv[u].size(); i++){
		int v = Ginv[u][i];
		if( Vis[v] == 0 ){
			dfs1(v);
		}
	}
	//cout<<u<<endl;
	O[io++]=u;
   
}

void dfs2(int u, int nscc){
	Vis[u] = 1;
	//cout<<u<<" "<<nscc<<endl;
	Etiqueta[u] = nscc;
	for(int i=0; i<G[u].size(); i++){
		int v = G[u][i];
		if( Vis[v] == 0 ){
			dfs2(v, nscc);
		}
	}
}

void kosaraju(){
	// Creando el grafo invertido :
	for(int i=0; i<8; i++){
		for(int j=0; j<G[i].size(); j++){
			int u = i;
			int v = G[i][j];
			Ginv[v].push_back(u);
		}
	}
	

	// Dfs1
	for(int i=0; i<8; i++) Vis[i] = 0;
	for(int i=0; i<8; i++){
		if(Vis[i]==0) dfs1(i);
	}
	
	// Dfs2
	for(int i=0; i<8; i++) Vis[i] = 0;
	int nscc = 1;
	for(int i=7; i>=0; i--){
		int u = O[i];
		if(Vis[u]==0){
			dfs2(u, nscc);
			nscc++;
		}
	}
	for(int i=0; i<8; i++){
		cout<<i<<" esta en la componente "<<Etiqueta[i]<<endl;
	}
}
	
int main(){
	int N = 8;
	G[1].push_back(2);
	G[1].push_back(3);
	G[3].push_back(2);
	G[2].push_back(6);
	G[6].push_back(7);
	G[7].push_back(2);
	G[2].push_back(4);
	G[4].push_back(0);
	G[0].push_back(5);
	G[5].push_back(4);
	G[4].push_back(5);
	kosaraju();
		
	return 0;
}
