// Add to mockServices object
const generatePhotos = () => Array.from({ length: 12 }, (_, i) => ({
  id: i + 1,
  title: `Photo ${i + 1}`,
  description: `Description de la photo ${i + 1}`,
  url: `https://picsum.photos/800/600?random=${i}`,
  category: ['Événements', 'Formation', 'Team Building', 'Célébrations'][i % 4],
  date: format(addDays(new Date(), -i), 'dd/MM/yyyy', { locale: fr })
}))

export const mockServices = {
  // ... existing services ...
  
  blogService: {
    // ... existing blog service methods ...
    getPhotos: () => Promise.resolve({ data: { data: generatePhotos() } })
  }
}