export default function Header() {
  return (
    <header className="flex justify-between items-center bg-white p-4 shadow-md">
      <h1 className="text-xl font-bold text-blue-800">Dashboard</h1>
      <div className="flex items-center space-x-4">
        <input type="text" placeholder="Search..." className="px-4 py-1 border rounded" />
        <div className="w-8 h-8 bg-blue-200 rounded-full"></div>
      </div>
    </header>
  );
}
